import React from 'react';
import { map } from 'lodash';

class Row extends React.Component {
  constructor() {
    super();

    this.createCampaignRow = this.createCampaignRow.bind(this);
    this.createCell = this.createCell.bind(this);
  }

  createCampaignRow(campaign) {
    const row = [
      {
        url: campaign ? `/campaigns/${campaign.id}` : '/campaigns',
        title: campaign ? campaign.title : 'Campaign Not Found',
      },
      {
        url: null,
        title: campaign ? campaign.pending_count : 0,
      },
      {
        url: campaign ? `/campaigns/${campaign.id}/inbox` : '/campaigns',
        title: 'review',
      }
    ];

    return row;
  }

  createCell(data) {
    if (data.url) {
      return <td className="table__cell"><a href={cell.url}>{cell.title}</a></td>
    }

    return <td className="table__cell">{cell.title}</td>
  }

  render() {
    const content = this.props.type === 'campaigns' ? this.createCampaignRow(this.props.data) : null;

    return (
      <tr className="table__row">
       {content.map(function(cell) {
        { if (cell.url) {
            return <td className="table__cell"><a href={cell.url}>{cell.title}</a></td>
          }

          return <td className="table__cell">{cell.title}</td>
        }
        })}
      </tr>
    )
  }
}

export default Row;
