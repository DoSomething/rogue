import React from 'react';

class Row extends React.Component {
  constructor() {
    super();

    this.createCampaignRow = this.createCampaignRow.bind(this);
  }

  createCampaignRow(campaign) {
    const campaignUrl = campaign ? `/campaigns/${campaign.d}` : '/campaigns';
    const campaignTitle = campaign ? campaign.title : 'Campaign Not Found';
    const pendingCount = campaign ? campaign.pending_count : 0;
    const inboxUrl = campaign ? `/campaigns/${campaign.id}/inbox` : '/campaigns';

    var row = [
      {
        url: campaignUrl,
        title: campaign ? campaign.title : 'Campaign Not Found',
      },
      {
        url: null,
        title: campaign ? campaign.pending_count : 0,
      },
      {
        url: inboxUrl,
        title: 'review',
      }
    ];

    return row;
  }

  render() {
    if (this.props.type === 'campaigns') {
      const content = this.createCampaignRow(this.props.data);
      console.log('here first');
      console.log(content);
    }

    return (
      <tr className="table__row">
      {console.log('here second')}
       {content.map(function(cell) {
          return <td className="table__cell"><a href={cell.url}>{cell.title}</a></td>
        })}
      </tr>
    )
  }
}

export default Row;
