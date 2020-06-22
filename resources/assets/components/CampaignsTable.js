import { get } from 'lodash';
import gql from 'graphql-tag';
import { Link } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';
import React, { useState, useEffect } from 'react';

import Empty from './Empty';
import { updateQuery } from '../helpers';
import SortableHeading from './utilities/SortableHeading';

const CAMPAIGNS_QUERY = gql`
  query CampaignsIndexQuery(
    $isOpen: Boolean
    $filter: String
    $cursor: String
  ) {
    campaigns: searchCampaigns(
      isOpen: $isOpen
      term: $filter
      cursor: $cursor
    ) {
      edges {
        cursor
        node {
          id
          internalTitle
          pendingCount
          acceptedCount
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
 * This component handles fetching & paginating a list of
 * campaigns matching the given filter(s).
 *
 * @param {Boolean} isOpen
 * @param {String} filter
 */
const CampaignsTable = ({ isOpen, filter }) => {
  const [orderBy, setOrderBy] = useState('pending_count,desc');

  const { error, loading, data, fetchMore } = useQuery(CAMPAIGNS_QUERY, {
    variables: { filter, isOpen /* orderBy */ },
    notifyOnNetworkStatusChange: true,
  });

  const campaigns = data ? data.campaigns.edges : [];
  const noFilteredResults = campaigns.length === 0 && !loading;
  const { endCursor, hasNextPage } = get(data, 'campaigns.pageInfo', {});

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

  if (noFilteredResults && !hasNextPage) {
    return <Empty />;
  }

  return (
    <>
      <table className="table">
        <thead>
          <tr>
            <SortableHeading
              column="id"
              label="Campaign ID"
              orderBy={orderBy}
              onChange={setOrderBy}
            />
            <SortableHeading
              column="pending_count"
              label="Pending"
              orderBy={orderBy}
              onChange={setOrderBy}
            />
            <SortableHeading
              column="accepted_count"
              label="Accepted"
              orderBy={orderBy}
              onChange={setOrderBy}
            />
            <td></td>
            <td></td>
          </tr>
        </thead>
        <tbody>
          {campaigns.map(({ node, cursor }) => (
            <tr key={cursor}>
              <td>
                <Link to={`/campaigns/${node.id}/accepted`}>
                  {node.internalTitle}{' '}
                  <code className="footnote">({node.id})</code>
                </Link>
              </td>
              <td>{node.pendingCount}</td>
              <td>{node.acceptedCount}</td>
              <td>
                <Link to={`/campaigns/${node.id}/pending`}>review</Link>
              </td>
              <td>
                <a href={`/campaigns/${node.id}`}>edit</a>
              </td>
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

export default CampaignsTable;
