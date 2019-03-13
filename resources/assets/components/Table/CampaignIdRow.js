import React from 'react';
import { map } from 'lodash';
import { format } from 'date-fns';
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
        title: campaign ? campaign.id : 'Campaign Not Found',
      },
      {
        url: campaign ? `/campaign-ids/${campaign.id}` : '/campaign-ids',
        title: campaign ? campaign.internal_title : 'Campaign Not Found',
      },
      {
        url: null,
        title: campaign ? campaign.cause : 'Campaign Not Found',
      },
      {
        url: campaign ? campaign.impact_doc : '/campaign-ids',
        title: 'Read',
      },
      {
        url: null,
        title: campaign
          ? format(
              new Date(campaign.start_date.replace(/-/g, '/')),
              'MM/dd/yyyy',
            )
          : '-',
      },
      {
        url: null,
        title:
          campaign && campaign.end_date
            ? format(
                new Date(campaign.end_date.replace(/-/g, '/')),
                'MM/dd/yyyy',
              )
            : '-',
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
