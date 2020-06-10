import gql from 'graphql-tag';
import React, { useState } from 'react';
import { useParams } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import NotFound from './NotFound';
import Empty from '../components/Empty';
import { formatDateTime } from '../helpers';
import Shell from '../components/utilities/Shell';
import MetaInformation from '../components/utilities/MetaInformation';

// @TODO: Paginate through signups.
const SHOW_GROUP_QUERY = gql`
  query ShowGroupQuery($id: Int!) {
    group(id: $id) {
      goal
      groupType {
        id
        name
      }
      name
    }
    signups(groupId: $id) {
      id
      userId
      campaign {
        id
        internalTitle
      }
      createdAt
    }
    posts(
      groupId: $id
      type: "voter-reg"
      status: [REGISTER_FORM, REGISTER_OVR]
    ) {
      id
      user {
        displayName
      }
    }
  }
`;

const ShowGroup = () => {
  const { id } = useParams();
  const title = `Group #${id}`;
  document.title = title;

  const { loading, error, data } = useQuery(SHOW_GROUP_QUERY, {
    variables: { id: Number(id) },
  });

  if (error) {
    return <Shell error={error} />;
  }

  if (loading) {
    return <Shell title={title} loading />;
  }

  if (!data.group) return <NotFound title={title} type="group" />;

  const { goal, groupType, name } = data.group;

  return (
    <Shell title={title} subtitle={`${name} (${groupType.name})`}>
      <div className="container__row">
        <div className="container__block -half">
          <MetaInformation
            details={{
              'Voter Registrations Goal': goal || '--',
              'Voter Registrations Completed': data.posts.length,
            }}
          />
        </div>
        <div className="container__block -half form-actions -inline text-right">
          <a className="button -tertiary" href={`/groups/${id}/edit`}>
            Edit Group
          </a>
        </div>
      </div>
      <div className="container__row">
        <div className="container__block">
          <h3>Signups</h3>
          {data.signups.length ? (
            <table className="table">
              <thead>
                <tr>
                  <td>ID</td>
                  <td>User</td>
                  <td>Campaign</td>
                  <td>Created</td>
                </tr>
              </thead>
              {data.signups.map(signup => (
                <tr>
                  <td>
                    <a href={`/signups/${signup.id}`}>{signup.id}</a>
                  </td>
                  <td>
                    <a href={`/users/${signup.userId}`}>{signup.userId}</a>
                  </td>
                  <td>
                    <a href={`/campaigns/${signup.campaign.id}`}>
                      {signup.campaign.internalTitle} ({signup.campaign.id})
                    </a>
                  </td>
                  <td>{formatDateTime(signup.createdAt)}</td>
                </tr>
              ))}
            </table>
          ) : (
            <Empty />
          )}
        </div>
      </div>
      <ul className="form-actions margin-vertical">
        <li>
          <a className="button -tertiary" href={`/group-types/${groupType.id}`}>
            View all {groupType.name} Groups
          </a>
        </li>
      </ul>
    </Shell>
  );
};

export default ShowGroup;
