import React from 'react';
import { map } from 'lodash';

import CampaignTable from '../CampaignTable';

class CampaignOverview extends React.Component {
  render() {
    const causeData = this.props;

    const causeTables = map(causeData, (data, cause) => (
      <CampaignTable
        key={cause}
        cause={cause}
        campaigns={data}
        causeData={causeData}
      />
    ));

    return <div className="container">{causeTables}</div>;
  }
}

export default CampaignOverview;
