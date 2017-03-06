import React from 'react';

import Header from './Header';
import Row from './Row';
import './table.scss';

class CampaignTable extends React.Component {
  render() {
    const campaigns = ['Campaign 1', 'Campain 2', 'Campaign 3'];
    const campaignsList = campaigns.map((name, index) => {
      return <Row key={index} campaign={name} />;
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
