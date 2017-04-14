import React from 'react';

class Row extends React.Component {
  render() {
    return (
      <tr className="table__row">
        <td className="table__cell"><a href="#">{this.props.campaign ? this.props.campaign.title : 'Campaign Not Found'}</a></td>
        <td className="table__cell"><a href={`/campaigns/${this.props.campaign_id}/inbox`}>{this.props.pending}</a></td>
        <td className="table__cell">{this.props.approved}</td>
        <td className="table__cell">{this.props.rejected}</td>
      </tr>
    )
  }
}

export default Row;
