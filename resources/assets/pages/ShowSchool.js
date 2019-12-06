import gql from 'graphql-tag';
import React, { useState } from 'react';
import { parse, format } from 'date-fns';
import { useParams } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import NotFound from './NotFound';
import Shell from '../components/utilities/Shell';
import MetaInformation from '../components/utilities/MetaInformation';

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
          <h2>Actions</h2>
          <table className="table">
            <thead>
              <tr>
                <td>Action</td>
                <td>Impact</td>
              </tr>
            </thead>
            <tbody>
              {schoolActionStats.map(item => (
                <tr key={item.action.id}>
                  <td>
                    <strong>
                      <a href={`/actions/${item.action.id}`}>
                        {item.action.name}
                      </a>
                    </strong>
                  </td>
                  <td>
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
