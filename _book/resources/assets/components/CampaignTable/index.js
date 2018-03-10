import React from 'react';
import PropTypes from 'prop-types';

import Table from '../Table';

class CampaignTable extends React.Component {
  render() {
    const cause = this.props.cause;
    const campaigns = this.props.campaigns;

    return (
      <div className="table-responsive container__block">
        <h2>{cause}</h2>
        <Table key={cause} className="table" headings={['Campaign Name', 'Inbox']} data={this.props.campaigns} type="campaigns" />
      </div>
    );
  }
}

CampaignTable.propTypes = {
  cause: PropTypes.string.isRequired,
  campaigns: PropTypes.array, // eslint-disable-line react/forbid-prop-types
};

CampaignTable.defaultProps = {
  campaigns: null,
};

export default CampaignTable;
