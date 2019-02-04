import React from 'react';
import PropTypes from 'prop-types';

import Table from '../Table';

class CampaignIdTable extends React.Component {
  render() {
    const campaigns = this.props.campaigns;

    return (
      <div className="table-responsive container__block">
        <Table
          className="table"
          headings={[
            'ID',
            'Internal Name',
            'Cause',
            'Impact',
            'Start Date',
            'End Date',
          ]}
          data={[campaigns]}
          type="campaignIds"
        />
      </div>
    );
  }
}

CampaignIdTable.propTypes = {
  campaigns: PropTypes.array,
};

CampaignIdTable.defaultProps = {
  campaigns: null,
};

export default CampaignIdTable;
