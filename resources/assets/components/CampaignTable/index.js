import React from 'react';

import Header from './Header';
import Row from './Row';
import './table.scss';

class CampaignTable extends React.Component {
  render() {
    const cause = this.props.cause;
    const campaigns = this.props.campaigns;

    const campaignsList = campaigns.map((campaign, index) => {
      return <Row key={index} campaign={campaign} />;
    });

    return (
      <div className="table-responsive container__block">
        <h2>{cause}</h2>
        <table className="table">
          <Header />
          <tbody>{campaignsList}</tbody>
        </table>
      </div>
    )
  }
}

export default CampaignTable;
