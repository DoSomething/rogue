import gql from 'graphql-tag';
import React, { useState } from 'react';
import { useParams } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import NotFound from './NotFound';
import Empty from '../components/Empty';
import Shell from '../components/utilities/Shell';
import MetaInformation from '../components/utilities/MetaInformation';

const SHOW_GROUP_TYPE_QUERY = gql`
  query ShowGroupTypeQuery($id: Int!) {
    groupType(id: $id) {
      createdAt
      name
    }
    groups(groupTypeId: $id) {
      id
      goal
      name
    }
    paginatedCampaigns(groupTypeId: $id) {
      edges {
        node {
          id
          internalTitle
        }
      }
    }
  }
`;

const ShowGroupType = () => {
  const { id } = useParams();
  const title = `Group Type #${id}`;
  document.title = title;

  const { loading, error, data } = useQuery(SHOW_GROUP_TYPE_QUERY, {
    variables: { id: Number(id) },
  });

  if (error) {
    return <Shell error={error} />;
  }

  if (loading) {
    return <Shell title={title} loading />;
  }

  if (!data.groupType) return <NotFound title={title} type="group type" />;

  const { createdAt, name } = data.groupType;
  const campaigns = data.paginatedCampaigns.edges;

  return (
    <Shell title={title} subtitle={name}>
      <div className="container__row">
        <div className="container__block -half">
          <MetaInformation
            details={{
              Campaigns: campaigns.length
                ? campaigns.map(item => (
                    <a key={item.node.id} href={`/campaigns/${item.node.id}`}>
                      {item.node.internalTitle}
                    </a>
                  ))
                : '--',
            }}
          />
        </div>
        <div className="container__block -half form-actions -inline text-right">
          <a className="button -tertiary" href={`/group-types/${id}/edit`}>
            Edit Group Type #{id}
          </a>
        </div>
      </div>
      <div className="container__row">
        <div className="container__block">
          {data.groups ? (
            <table className="table">
              <thead>
                <tr>
                  <td>Group ID</td>
                  <td>Goal</td>
                </tr>
              </thead>
              <tbody>
                {data.groups.map(group => (
                  <tr key={group.id}>
                    <td>
                      <a href={`/groups/${group.id}`}>
                        {group.name} ({group.id})
                      </a>
                    </td>
                    <td>{group.goal || '--'}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          ) : (
            <Empty />
          )}
          <div className="container__block -narrow">
            <a
              className="button -primary"
              href={`/group-types/${id}/groups/create`}
            >
              Add Group
            </a>
          </div>
        </div>
      </div>
      <ul className="form-actions margin-vertical">
        <li>
          <a className="button -tertiary" href={`/group-types`}>
            View all Group Types
          </a>
        </li>
      </ul>
    </Shell>
  );
};

export default ShowGroupType;
