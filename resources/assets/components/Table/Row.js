import React from 'react';

class Row extends React.Component {

  defaultProps: {
      campaign: {
        title: 'No Title',
      }
    };

  render() {
    return (
      <tr className="table__row">
        <td className="table__cell"><a href="#">{this.props.campaign.title}</a></td>
        <td className="table__cell">{this.props.approved}</td>
        <td className="table__cell">{this.props.pending}</td>
        <td className="table__cell">{this.props.rejected}</td>
        <td className="table__cell">{this.props.deleted}</td>
      </tr>
    )
  }
}

export default Row;
