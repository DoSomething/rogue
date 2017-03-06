import React from 'react';

class Row extends React.Component {
  render() {
    return (
      <tr className="table__row">
        <td className="table__cell"><a href="#">{this.props.campaign}</a></td>
        <td className="table__cell">13</td>
        <td className="table__cell">25</td>
        <td className="table__cell">34</td>
        <td className="table__cell">40</td>
      </tr>
    )
  }
}

export default Row;
