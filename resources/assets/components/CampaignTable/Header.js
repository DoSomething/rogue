import React from 'react';

class Header extends React.Component {
  render() {
    return (
      <thead>
        <tr className="table__header">
          <th className="table__cell -delta">
            <h3 className="heading -delta">Campaign Name</h3>
          </th>
          <th className="table__cell">
            <h3 className="heading -delta">Approved</h3>
          </th>
          <th className="table__cell">
            <h3 className="heading -delta">Pending</h3>
          </th>
          <th className="table__cell">
            <h3 className="heading -delta">Rejected</h3>
          </th>
          <th className="table__cell">
            <h3 className="heading -delta">Deleted</h3>
          </th>
        </tr>
      </thead>
    )
  }
}

export default Header;
