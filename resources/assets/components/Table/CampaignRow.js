import React from 'react';
import { map } from 'lodash';
import PropTypes from 'prop-types';

class CampaignRow extends React.Component {
  constructor() {
    super();

    this.createCampaignRow = this.createCampaignRow.bind(this);
  }

  createCampaignRow(campaign) {
    const row = [
      {
        url: campaign ? `/campaigns/${campaign.id}` : '/campaigns',
        title: campaign ? campaign.title : 'Campaign Not Found',
      },
      {
        url: campaign ? `/campaigns/${campaign.id}/inbox` : '/campaigns',
        title: 'Review',
      },
    ];

    return row;
  }

  render() {
    const content = this.createCampaignRow(this.props.data);

    return (
      <tr className="table__row">
        {content.map((cell, index) => {
          {
            if (cell.url) {
              return <td className="table__cell" key={index}><a href={cell.url}>{cell.title}</a></td>;
            }

            return <td className="table__cell" key={index}>{cell.title}</td>;
          }
        })}
      </tr>
    );
  }
}

CampaignRow.propTypes = {
  data: PropTypes.object, // eslint-disable-line react/forbid-prop-types
};

CampaignRow.defaultProps = {
  data: null,
};

export default CampaignRow;
