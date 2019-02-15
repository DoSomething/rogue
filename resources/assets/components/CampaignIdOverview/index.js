import React from 'react';

import CampaignIdTable from '../CampaignIdTable';

class CampaignIdOverview extends React.Component {
  render() {
    const campaigns = this.props;
    const campaignIdTable = <CampaignIdTable campaigns={campaigns} />;

    return <div className="container">{campaignIdTable}</div>;
  }
}

export default CampaignIdOverview;
