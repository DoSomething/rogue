import React from 'react';

const UserShell = ({ children }) => (
  <div>
    <header className="header" role="banner">
      <div className="wrapper">
        <h1 className="header__title">Members</h1>
        <p className="header__subtitle">User profiles &amp; signups...</p>
      </div>
    </header>
    <div className="container">
      <div className="wrapper">{children}</div>
    </div>
  </div>
);

export default UserShell;
