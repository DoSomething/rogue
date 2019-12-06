import gql from 'graphql-tag';
import React, { useState } from 'react';
import { parse, format } from 'date-fns';
import { useParams } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import NotFound from './NotFound';
import Shell from '../components/utilities/Shell';
import MetaInformation from '../components/utilities/MetaInformation';

// @TODO: Add support for paging through schoolActionStats once more actions collect school.
const SHOW_SCHOOL_QUERY = gql`
  query ShowSchoolQuery($id: String!) {
    school(id: $id) {
      id
      name
      city
      state
      schoolActionStats {
        action {
          id
          name
          noun
          verb
          campaign {
            id
            internalTitle
          }
        }
        acceptedQuantity
        updatedAt
      }
    }
  }
`;

const ShowSchool = () => {
  const { id } = useParams();
  const title = `School #${id}`;
  document.title = title;

  const { loading, error, data } = useQuery(SHOW_SCHOOL_QUERY, {
    variables: { id },
  });

  if (error) {
    return <Shell error={error} />;
  }

  if (loading) {
    return <Shell title={title} loading />;
  }

  if (!data.school) return <NotFound title={title} type="school" />;

  const { city, name, schoolActionStats, state } = data.school;

  return (
    <Shell title={title} subtitle={name}>
      <div className="container__row">
        <div className="container__block">
          <MetaInformation
            details={{
              ID: id,
              City: city,
              State: state,
            }}
          />
        </div>
      </div>
      <div className="container__row">
        <div className="container__block">
          <h3>Aggregate Impact</h3>
          <p className="mb-4">
            These totals are updated any time a Review is created for a Post
            that is associated with this School.
          </p>
          <table className="table">
            <thead>
              <tr>
                <td>Action</td>
                <td>Campaign</td>
                <td className="text-center">Total approved quantity</td>
              </tr>
            </thead>
            <tbody>
              {schoolActionStats.map(item => (
                <tr key={item.action.id}>
                  <td>
                    <a href={`/actions/${item.action.id}`}>
                      {item.action.name}
                    </a>
                  </td>
                  <td>
                    <a href={`/campaigns/${item.action.campaign.id}`}>
                      {item.action.campaign.internalTitle}
                    </a>
                  </td>
                  <td className="text-center">
                    <strong>{item.acceptedQuantity}</strong> {item.action.noun}{' '}
                    {item.action.verb}
                    <div className="text-sm">
                      Updated {format(parse(item.updatedAt), 'M/D/YYYY h:mm a')}
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </Shell>
  );
};

export default ShowSchool;
