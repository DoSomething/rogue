import React from 'react';

import Table from '../Table';

class CampaignTable extends React.Component {
  render() {
    const cause = this.props.cause;
    const campaigns = this.props.campaigns;

    return (
      <div className="table-responsive container__block">
        <h2>{cause}</h2>
        <Table key={cause} className="table" headings={['Campaign Name', 'Pending', 'Inbox']} data={this.props.campaigns} />
      </div>
    )
  }
}

export default CampaignTable;
