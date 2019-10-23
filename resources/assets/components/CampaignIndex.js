import gql from 'graphql-tag';
import { merge } from 'lodash';
import React, { useState } from 'react';
import { useQuery } from '@apollo/react-hooks';

import Empty from './Empty';

const CAMPAIGNS_QUERY = gql`
  query CampaignsOverviewQuery($isOpen: Boolean!, $page: Int!) {
    campaigns(
      isOpen: $isOpen
      orderBy: "pending_count,desc"
      count: 15
      page: $page
    ) {
      id
      internalTitle
      pendingCount
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

    const matchesId = campaign.id.toString().includes(search);
    const matchesTitle = campaign.internalTitle.toLowerCase().includes(search);
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

  const campaigns = filterCampaigns(data.campaigns, filter);

  return campaigns.length ? (
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
        {campaigns.map(campaign => (
          <tr className="table__row" key={campaign.id}>
            <td className="table__cell">
              <a href="">
                {campaign.internalTitle}{' '}
                <code className="footnote">({campaign.id})</code>
              </a>
            </td>
            <td className="table__cell">{campaign.pendingCount || 0}</td>
            <td className="table__cell">
              <a href={`/campaigns/${campaign.id}/inbox`}>review</a>
            </td>
            <td className="table__cell">
              <a href={`/campaign-ids/${campaign.id}`}>edit</a>
            </td>
          </tr>
        ))}
      </tbody>
      <tfoot>
        <tr>
          <td>
            <button
              className="button -tertiary"
              onClick={() => {
                fetchMore({
                  variables: {
                    // The value in `variables.page` doesn't get updated here on
                    // subsequent clicks, so we have to recalculate each time...
                    page: Math.ceil(data.campaigns.length / 10) + 1,
                  },
                  updateQuery: (previous, { fetchMoreResult }) => {
                    if (!fetchMoreResult) return previous;
                    return {
                      ...previous,
                      campaigns: [
                        ...previous.campaigns,
                        ...fetchMoreResult.campaigns,
                      ],
                    };
                  },
                });
                // updateQuery: (previous, { fetchMoreResult }) =>
                //   merge(previous, fetchMoreResult),
                // },
              }}
            >
              view more...
            </button>
          </td>
        </tr>
      </tfoot>
    </table>
  ) : (
    <Empty />
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
