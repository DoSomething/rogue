import { get } from 'lodash';
import gql from 'graphql-tag';
import { Link } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';
import React, { useState, useEffect } from 'react';

import Empty from './Empty';
import EntityLabel from './utilities/EntityLabel';
import { formatDateTime, updateQuery } from '../helpers';

const SIGNUPS_TABLE_QUERY = gql`
  query SignupsIndexQuery($groupId: Int, $cursor: String) {
    signups: paginatedSignups(after: $cursor, first: 50, groupId: $groupId) {
      edges {
        cursor
        node {
          id
          userId
          campaign {
            id
            internalTitle
          }
          createdAt
        }
      }
      pageInfo {
        endCursor
        hasNextPage
      }
    }
  }
`;

/**
 * This component handles fetching & paginating a list of signups.
 *
 * @param {Number} groupId
 */
const SignupsTable = ({ groupId }) => {
  const { error, loading, data, fetchMore } = useQuery(SIGNUPS_TABLE_QUERY, {
    variables: { groupId },
    notifyOnNetworkStatusChange: true,
  });

  const signups = data ? data.signups.edges : [];
  const noResults = signups.length === 0 && !loading;
  const { endCursor, hasNextPage } = get(data, 'signups.pageInfo', {});

  const handleViewMore = () => {
    fetchMore({
      variables: { cursor: endCursor },
      updateQuery,
    });
  };

  if (error) {
    return (
      <div className="text-center">
        <p>There was an error. :(</p>
        <code>{JSON.stringify(error)}</code>
      </div>
    );
  }

  if (noResults && !hasNextPage) {
    return (
      <Empty
        copy={
          groupId
            ? `No signups found for group #${groupId}.`
            : 'No signups found.'
        }
      />
    );
  }

  return (
    <>
      <table className="table">
        <thead>
          <tr>
            <td>Signup</td>
            <td>User</td>
            <td>Campaign</td>
          </tr>
        </thead>
        <tbody>
          {signups.map(({ node, cursor }) => (
            <tr key={cursor}>
              <td>
                <Link to={`/signups/${node.id}`}>
                  {formatDateTime(node.createdAt)}
                </Link>
              </td>
              <td>
                <Link to={`/users/${node.userId}`}>{node.userId}</Link>
              </td>
              <td>
                <EntityLabel
                  id={node.campaign.id}
                  name={node.campaign.internalTitle}
                  path="campaigns"
                />
              </td>
            </tr>
          ))}
        </tbody>
        <tfoot className="form-actions">
          {loading ? (
            <tr>
              <td colSpan="2">
                <div className="spinner margin-horizontal-auto margin-vertical" />
              </td>
            </tr>
          ) : null}
          {hasNextPage ? (
            <tr>
              <td colSpan="2">
                <button
                  className="button -tertiary"
                  onClick={handleViewMore}
                  disabled={loading}
                >
                  view more...
                </button>
              </td>
            </tr>
          ) : null}
        </tfoot>
      </table>
    </>
  );
};

export default SignupsTable;
