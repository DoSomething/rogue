import React from 'react';

import Header from './Header';
import Row from './Row';
import './table.scss';

class CampaignTable extends React.Component {
  render() {
    const campaigns = [
      {
        name: 'Campaign 1',
        approved: 42,
        pending: 32,
        rejected: 34,
        deleted: 3,
      },
      {
        name: 'Campaign 2',
        approved: 42,
        pending: 32,
        rejected: 34,
        deleted: 3,
      },
      {
        name: 'Campaign 3',
        approved: 42,
        pending: 32,
        rejected: 34,
        deleted: 3,
      },
    ];

    const campaignsList = campaigns.map((campaign, index) => {
      return <Row key={index} campaign={campaign} />;
    });

    return (
      <div className="table-responsive container__block">
        <h2>Table Header</h2>
        <table className="table">
          <Header />
          <tbody>{campaignsList}</tbody>
        </table>
      </div>
    )
  }
}

export default CampaignTable;
