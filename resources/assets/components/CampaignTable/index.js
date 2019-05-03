import React from 'react';
import PropTypes from 'prop-types';

import Table from '../Table';

class CampaignTable extends React.Component {
  render() {
    const campaigns = this.props.campaigns;

    return (
      <div className="table-responsive">
        <Table
          className="table"
          headings={['Campaign Name', 'Pending', 'Inbox']}
          data={this.props.campaigns}
          type="campaigns"
        />
      </div>
    );
  }
}

CampaignTable.propTypes = {
  campaigns: PropTypes.array, // eslint-disable-line react/forbid-prop-types
};

CampaignTable.defaultProps = {
  campaigns: null,
};

export default CampaignTable;
