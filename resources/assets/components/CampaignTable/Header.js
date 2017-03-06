import React from 'react';

class Header extends React.Component {
  render() {
    return (
      <thead>
        <tr>
          <th>Campaign Name</th>
          <th>Approved</th>
          <th>Pending</th>
          <th>Rejecte</th>
          <th>Deleted</th>
        </tr>
      </thead>
    )
  }
}

export default Header;
