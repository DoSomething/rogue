import React from 'react';

import CampaignTable from '../CampaignTable';

class CampaignOverview extends React.Component {
  render() {
    const causeData = this.props;

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
