import React from 'react';
import { map } from 'lodash';
import PropTypes from 'prop-types';

class CampaignIdRow extends React.Component {
  constructor() {
    super();

    this.createCampaignIdRow = this.createCampaignIdRow.bind(this);
  }

  createCampaignIdRow(campaign) {
    const row = [
      {
        url: null,
        title: campaign.id,
      },
      {
        url: null,
        title: campaign.internal_name,
      },
      {
        url: null,
        title: campaign.cause,
      },
      {
        url: campaign.impact_doc,
        title: 'Read',
      },
      {
        url: null,
        title: campaign.start_date,
      },
      {
        url: null,
        title: campaign.end_date ? campaign.end_date : '-',
      },
    ];

    return row;
  }

  render() {
    const content = this.createCampaignIdRow(this.props.data);

    return (
      <tr className="table__row">
        {content.map((cell, index) => {
          {
            if (cell.url) {
              return (
                <td className="table__cell" key={index}>
                  <a href={cell.url}>{cell.title}</a>
                </td>
              );
            }

            return (
              <td className="table__cell" key={index}>
                {cell.title}
              </td>
            );
          }
        })}
      </tr>
    );
  }
}

CampaignIdRow.PropTypes = {
  data: PropTypes.object,
};

CampaignIdRow.defaultProps = {
  data: null,
};

export default CampaignIdRow;
