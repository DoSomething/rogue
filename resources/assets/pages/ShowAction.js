import gql from 'graphql-tag';
import React, { useState } from 'react';
import { useParams } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import NotFound from './NotFound';
import Action, { ActionFragment } from '../components/Action';
import Shell from '../components/utilities/Shell';
import MetaInformation from '../components/utilities/MetaInformation';

const SHOW_ACTION_QUERY = gql`
  query ShowActionQuery($id: Int!) {
    action(id: $id) {
      ...ActionFragment
      schoolActionStats {
        schoolId
        school {
          id
          name
          city
          state
        }
        acceptedQuantity
      }
    }
  }
  ${ActionFragment}
`;

const ShowAction = () => {
  const { id } = useParams();
  const title = `Action #${id}`;

  const { loading, error, data } = useQuery(SHOW_ACTION_QUERY, {
    variables: { id: Number(id) },
  });

  if (error) {
    return <Shell error={error} />;
  }

  if (loading) {
    return <Shell title={title} loading />;
  }

  if (!data.action) {
    return <NotFound title={title} type="action" />;
  }

  const { campaign, name, noun, schoolActionStats, verb } = data.action;

  return (
    <Shell title={title} subtitle={name}>
      <Action action={data.action} isPermalink />
      <ul className="form-actions margin-vertical">
        <li>
          <a className="button -tertiary" href={`/campaigns/${campaign.id}`}>
            View all Actions for Campaign {campaign.internalTitle}
          </a>
        </li>
      </ul>
      {schoolActionStats.length ? (
        <div className="mb-4">
          <h2>Schools</h2>
          <table className="table">
            <thead>
              <tr>
                <td>School Name</td>
                <td>Location</td>
                <td className="capitalize">
                  {noun} {verb}
                </td>
              </tr>
            </thead>
            <tbody>
              {schoolActionStats.map(item => (
                <tr key={item.school.id}>
                  <td>
                    <strong>
                      <a href={`/schools/${item.school.id}`}>
                        {item.school.name}
                      </a>
                    </strong>
                  </td>
                  <td>
                    {item.school.city}, {item.school.state}
                  </td>
                  <td>
                    <strong>{item.acceptedQuantity}</strong>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      ) : null}
    </Shell>
  );
};

export default ShowAction;
