import React from 'react';

class Header extends React.Component {
  render() {
    return (
      <thead>
        <tr className="table__header">
          <th className="table__cell">Campaign Name</th>
          <th className="table__cell">Approved</th>
          <th className="table__cell">Pending</th>
          <th className="table__cell">Rejecte</th>
          <th className="table__cell">Deleted</th>
        </tr>
      </thead>
    )
  }
}

export default Header;
