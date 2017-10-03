import React from 'react';

class HistoryModalRow extends React.Component {
  render() {
    const newQuantity = this.props.data.content.quantity ? this.props.data.content.quantity : this.props.data.content.quantity_pending;

    return (
      <tr className="table__row">
        <td className="table__cell"> {newQuantity} </td>
        <td className="table__cell"> {this.props.data.content.why_participated} </td>
        <td className="table__cell"> {this.props.data.content.updated_at} </td>
        <td className="table__cell"> {this.props.data.user} </td>
      </tr>
    )
  }
}

export default HistoryModalRow;
