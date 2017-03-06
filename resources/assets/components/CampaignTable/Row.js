import React from 'react';

class Row extends React.Component {
  render() {
    return (
      <tr className="table__row">
        <td className="table__cell"><a href="#">{this.props.campaign.name}</a></td>
        <td className="table__cell">{this.props.campaign.approved}</td>
        <td className="table__cell">{this.props.campaign.pending}</td>
        <td className="table__cell">{this.props.campaign.rejected}</td>
        <td className="table__cell">{this.props.campaign.deleted}</td>
      </tr>
    )
  }
}

export default Row;
