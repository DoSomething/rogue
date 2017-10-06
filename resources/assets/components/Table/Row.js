import React from 'react';
import { map } from 'lodash';

class Row extends React.Component {
  constructor() {
    super();

    this.createCampaignRow = this.createCampaignRow.bind(this);
    this.createEventRow = this.createEventRow.bind(this);
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

  createEventRow(event) {
    const newQuantity = event.content.quantity ? event.content.quantity : event.content.quantity_pending;

    const row = [
      {
        url: null,
        title: newQuantity,
      },
      {
        url: null,
        title: event.content.why_participated,
      },
      {
        url: null,
        title: event.content.updated_at,
      },
      {
        url: null,
        title: event.user,
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
    const content = this.props.type === 'campaigns' ? this.createCampaignRow(this.props.data) : this.createEventRow(this.props.data);

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
