import { get } from 'lodash';
import gql from 'graphql-tag';
import { Link } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';
import React, { useState, useEffect } from 'react';

import Empty from './Empty';
import { updateQuery } from '../helpers';

const GROUPS_QUERY = gql`
  query GroupsIndexQuery($groupTypeId: Int!, $cursor: String) {
    groups: paginatedGroups(groupTypeId: $groupTypeId, after: $cursor) {
      edges {
        cursor
        node {
          id
          name
          goal
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
 * This component handles fetching & paginating a list of groups by group type ID.
 *
 * @param {Number} groupTypeId
 * @param {String} filter
 */
const GroupsTable = ({ groupTypeId }) => {
  const { error, loading, data, fetchMore } = useQuery(GROUPS_QUERY, {
    variables: { groupTypeId },
    notifyOnNetworkStatusChange: true,
  });

  if (loading) {
    return <div className="spinner margin-horizontal-auto margin-vertical" />;
  }

  const groups = data.groups.edges;
  const noResults = groups.length === 0 && !loading;
  const { endCursor, hasNextPage } = get(data, 'groups.pageInfo', {});

  // We can use this function to load more results:
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
    return <Empty />;
  }

  return (
    <>
      <table className="table">
        <thead>
          <tr>
            <td>Group ID</td>
            <td>Goal</td>
          </tr>
        </thead>
        <tbody>
          {groups.map(({ node, cursor }) => (
            <tr key={cursor}>
              <td>
                <Link to={`/groups/${node.id}`}>
                  {node.name} <code className="footnote">({node.id})</code>
                </Link>
              </td>
              <td>{node.goal || '--'}</td>
            </tr>
          ))}
        </tbody>
        <tfoot className="form-actions">
          {loading ? (
            <tr>
              <td colSpan="5">
                <div className="spinner margin-horizontal-auto margin-vertical" />
              </td>
            </tr>
          ) : null}
          {hasNextPage ? (
            <tr>
              <td colSpan="5">
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

export default GroupsTable;
