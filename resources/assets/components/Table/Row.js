import React from 'react';

class Row extends React.Component {
  render() {
    const { campaign } = this.props;
    const campaignUrl = this.props.campaign_id ? `/campaigns/${this.props.campaign_id}` : '/campaigns';
    const inboxUrl = this.props.campaign_id ? `/campaigns/${this.props.campaign_id}/inbox` : '/campaigns';

    return (
      <tr className="table__row">
        <td className="table__cell"><a href={campaignUrl}>{campaign ? campaign.title : 'Campaign Not Found'}</a></td>
        <td className="table__cell"><a href={inboxUrl}>{this.props.pending}</a></td>
      </tr>
    )
  }
}

export default Row;
