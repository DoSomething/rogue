import React from 'react';
import PropTypes from 'prop-types';

import Table from '../Table';

class CampaignIdTable extends React.Component {
  render() {
    const campaigns = this.props.campaigns;

    return (
      <div className="table-responsive container__block">
        <h1>All Campaign IDs</h1>
        <p>
          These are all the campaign IDs associated with their campaign name,
          start date, and if applicable, end date.
        </p>

        <div className="container__block -narrow">
          <a className="button -secondary" href={`/campaign-ids/create`}>
            New Campaign ID
          </a>
        </div>

        <Table
          className="table"
          headings={[
            'ID',
            'Internal Name',
            'Causes',
            'Impact',
            'Start Date',
            'End Date',
          ]}
          data={Object.values(campaigns)}
          type="campaignIds"
        />
      </div>
    );
  }
}

CampaignIdTable.propTypes = {
  campaigns: PropTypes.object,
};

CampaignIdTable.defaultProps = {
  campaigns: null,
};

export default CampaignIdTable;
