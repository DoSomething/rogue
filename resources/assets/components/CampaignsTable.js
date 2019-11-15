import { get } from 'lodash';
import gql from 'graphql-tag';
import { Link } from 'react-router-dom';
import React, { useEffect } from 'react';
import { useQuery } from '@apollo/react-hooks';

import Empty from './Empty';
import { updateQuery } from '../helpers';

const CAMPAIGNS_QUERY = gql`
  query CampaignsIndexQuery($isOpen: Boolean!, $cursor: String) {
    campaigns: paginatedCampaigns(
      isOpen: $isOpen
      orderBy: "pending_count,desc"
      after: $cursor
      first: 80
    ) {
      edges {
        cursor
        node {
          id
          internalTitle
          pendingCount
          acceptedCount
          causes {
            name
          }
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
 * Filter the given campaigns by current search term.
 *
 * @param  {Object} data - GraphQL response
 * @return {Object}
 */
const filterCampaigns = (data, filter) => {
  const search = filter.toLowerCase();

  if (!data) {
    return [];
  }

  if (filter === '') {
    return data.campaigns.edges;
  }

  return data.campaigns.edges.filter(campaign => {
    const { id, internalTitle, causes } = campaign.node;

    const matchesId = id.toString().includes(search);
    const matchesTitle = internalTitle.toLowerCase().includes(search);
    const matchesCause = causes.some(cause =>
      cause.name.toLowerCase().includes(search),
    );

    return matchesId || matchesTitle || matchesCause;
  });
};

/**
 * This component handles fetching & paginating a list of
 * campaigns matching the given filter(s).
 *
 * @param {Boolean} isOpen
 * @param {String} filter
 */
const CampaignsTable = ({ isOpen, filter }) => {
  const { error, loading, data, fetchMore } = useQuery(CAMPAIGNS_QUERY, {
    variables: { isOpen },
    notifyOnNetworkStatusChange: true,
  });

  const campaigns = filterCampaigns(data, filter);
  const noFilteredResults = campaigns.length === 0 && !loading;
  const { endCursor, hasNextPage } = get(data, 'campaigns.pageInfo', {});

  // We can use this function to load more results:
  const handleViewMore = () => {
    fetchMore({
      variables: { cursor: endCursor },
      updateQuery,
    });
  };

  // If we've filtered all results & can load more, do so automatically:
  useEffect(() => {
    if (noFilteredResults && hasNextPage) {
      handleViewMore();
    }
  }, [filter, endCursor]);

  if (error) {
    return 'There was an error. :(';
  }

  if (noFilteredResults && !hasNextPage) {
    return <Empty />;
  }

  return (
    <>
      <table className="table">
        <thead>
          <tr>
            <td>Campaign</td>
            <td>Pending</td>
            <td>Accepted</td>
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
                <a href={`/campaign-ids/${node.id}`}>edit</a>
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
