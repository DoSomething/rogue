import gql from 'graphql-tag';
import React, { useState } from 'react';
import { useQuery } from '@apollo/react-hooks';

import Empty from './Empty';
import { updateQuery } from '../helpers';

const CAMPAIGNS_QUERY = gql`
  query CampaignsOverviewQuery($isOpen: Boolean!, $cursor: String) {
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
 * @param  {Array} campaigns
 * @return {Array}
 */
const filterCampaigns = (campaigns, filter) => {
  const search = filter.toLowerCase();

  return campaigns.filter(campaign => {
    if (search === '') {
      return true;
    }

    const matchesId = campaign.node.id.toString().includes(search);
    const matchesTitle = campaign.node.internalTitle
      .toLowerCase()
      .includes(search);
    // const matchesCause = campaign.cause.some(cause =>
    //   cause.toLowerCase().includes(search),
    // );

    return matchesId || matchesTitle;
  });
};

/**
 * Filter and list the given campaigns.
 *
 * @param {Array} campaigns
 * @param {String} filter
 */
const CampaignsTable = ({ isOpen, filter }) => {
  const { loading, error, data, fetchMore } = useQuery(CAMPAIGNS_QUERY, {
    variables: { isOpen, page: 1 },
  });

  if (error) {
    return 'Error :(';
  }

  if (loading) {
    return <div className="spinner" />;
  }

  const campaigns = filterCampaigns(data.campaigns.edges, filter);
  const { endCursor, hasNextPage } = data.campaigns.pageInfo;
  const handleViewMore = () =>
    fetchMore({
      variables: { cursor: endCursor },
      updateQuery,
    });

  if (campaigns.length === 0 && !hasNextPage) {
    return <Empty />;
  }

  return (
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
              <a href="">
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
      <tfoot>
        <tr>
          <td>
            {hasNextPage ? (
              <button className="button -tertiary" onClick={handleViewMore}>
                view more...
              </button>
            ) : null}
          </td>
        </tr>
      </tfoot>
    </table>
  );
};

const CampaignOverview = () => {
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
            <div className="container__block">
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

export default CampaignOverview;
