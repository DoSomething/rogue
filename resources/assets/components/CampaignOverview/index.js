import React from 'react';

import CampaignTable from '../CampaignTable';

class CampaignOverview extends React.Component {
  render() {
    const { pending, etc } = this.props;

    return (
      <div className="container">
        <CampaignTable cause="Pending Review" campaigns={pending} />
        <CampaignTable cause="etc." campaigns={etc} />
      </div>
    );
  }
}

export default CampaignOverview;
