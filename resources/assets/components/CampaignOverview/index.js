import React from 'react';

import CampaignTable from '../CampaignTable';

class CampaignOverview extends React.Component {
  render() {
    const causeData = {
      'Staff Picks': [
        {
          name: 'Campaign 1',
          approved: 52,
          pending: 32,
          rejected: 34,
          deleted: 3,
        },
        {
          name: 'Campaign 2',
          approved: 62,
          pending: 32,
          rejected: 34,
          deleted: 3,
        },
        {
          name: 'Campaign 3',
          approved: 72,
          pending: 32,
          rejected: 34,
          deleted: 3,
        },
      ],
      'Environment': [
        {
          name: 'Campaign 1',
          approved: 52,
          pending: 32,
          rejected: 34,
          deleted: 3,
        },
        {
          name: 'Campaign 2',
          approved: 62,
          pending: 32,
          rejected: 34,
          deleted: 3,
        },
        {
          name: 'Campaign 3',
          approved: 72,
          pending: 32,
          rejected: 34,
          deleted: 3,
        },
      ],
      'Bullying': [
        {
          name: 'Campaign 1',
          approved: 52,
          pending: 32,
          rejected: 34,
          deleted: 3,
        },
        {
          name: 'Campaign 2',
          approved: 62,
          pending: 32,
          rejected: 34,
          deleted: 3,
        },
        {
          name: 'Campaign 3',
          approved: 72,
          pending: 32,
          rejected: 34,
          deleted: 3,
        },
      ]
    };

    const causeTables = Object.keys(causeData).map((cause, index) => {
      return <CampaignTable key={index} cause={cause} campaigns={causeData[cause]}/>;
    });

    return (
      <div className="container">
        {causeTables}
      </div>
    )
  }
}

export default CampaignOverview;
