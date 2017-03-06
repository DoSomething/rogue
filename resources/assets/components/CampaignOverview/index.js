import React from 'react';

import CampaignTable from '../CampaignTable';

class CampaignOverview extends React.Component {
  render() {
    return (
      <div className="container__block">
        <CampaignTable />
        <CampaignTable />
        <CampaignTable />
      </div>
    )
  }
}

export default CampaignOverview;
