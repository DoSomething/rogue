import { get } from 'lodash';
import gql from 'graphql-tag';
import { useQuery } from '@apollo/react-hooks';
import React, { useState, useEffect } from 'react';

import Empty from './Empty';
import { updateQuery } from '../helpers';

const CAMPAIGNS_QUERY = gql`
  query CampaignsIndexQuery($isOpen: Boolean!, $cursor: String) {
    campaigns: paginatedCampaigns(
      isOpen: $isOpen
      orderBy: "pending_count,desc"
      after: $cursor
      first: 15
    ) {
      edges {
        cursor
        node {
          id
          internalTitle
          pendingCount
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

  return data.campaigns.edges.filter(campaign => {
    if (search === '') {
      return true;
    }

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
 * This component handles fetching & paginating the campaign
 * tables on this page (for either open or closed campaigns).
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
          <tr className="table__header">
            <td className="table__cell">Campaign</td>
            <td className="table__cell">Pending</td>
            <td className="table__cell"></td>
            <td className="table__cell"></td>
          </tr>
        </thead>
        <tbody>
          {campaigns.map(({ node, cursor }) => (
            <tr className="table__row" key={cursor}>
              <td className="table__cell">
                <a href={`/campaigns/${node.id}`}>
                  {node.internalTitle}{' '}
                  <code className="footnote">({node.id})</code>
                </a>
              </td>
              <td className="table__cell">{node.pendingCount || 0}</td>
              <td className="table__cell">
                <a href={`/campaigns/${node.id}/inbox`}>review</a>
              </td>
              <td className="table__cell">
                <a href={`/campaign-ids/${node.id}`}>edit</a>
              </td>
            </tr>
          ))}
        </tbody>
        <tfoot className="form-actions">
          {loading ? (
            <tr>
              <td colspan="4">
                <div className="spinner margin-horizontal-auto margin-vertical" />
              </td>
            </tr>
          ) : null}
          {hasNextPage ? (
            <tr>
              <td colspan="4">
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

/**
 * The campaign index page!
 */
const CampaignIndex = () => {
  const [showClosed, setShowClosed] = useState(false);
  const [filter, setFilter] = useState('');

  const onChange = event => {
    setFilter(event.target.value);
  };

  return (
    <>
      <header className="header" role="banner">
        <div className="wrapper">
          <h1 className="header__title">Campaigns</h1>
          <p className="header__subtitle">
            Reportback inbox &amp; management...
          </p>
        </div>
      </header>
      <div className="container">
        <div className="wrapper">
          <div className="container__block -half">
            <input
              type="text"
              className="text-field -search"
              placeholder="Filter by campaign ID, name, cause..."
              onChange={onChange}
            />
          </div>
          <div className="container__block -half form-actions -inline text-right">
            <a className="button -tertiary" href="/campaign-ids/create">
              New Campaign
            </a>
          </div>
          <div className="container__block">
            <h3>Open Campaigns</h3>
            <p>These campaigns are currently accepting new submissions.</p>
          </div>
          <div className="container__block">
            <CampaignsTable isOpen={true} filter={filter} />
          </div>
          {showClosed ? (
            <>
              <div className="container__block">
                <h3>Closed Campaigns</h3>
                <p>These campaigns are no longer accepting new submissions.</p>
              </div>
              <div className="container__block">
                <CampaignsTable isOpen={false} filter={filter} />
              </div>
            </>
          ) : (
            <div className="container__block form-actions">
              <button
                className="button -tertiary"
                onClick={() => setShowClosed(true)}
              >
                show closed campaigns
              </button>
            </div>
          )}
        </div>
      </div>
    </>
  );
};

export default CampaignIndex;
