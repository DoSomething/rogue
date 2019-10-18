import React from 'react';

const UserShell = ({ children }) => (
  <div>
    <header class="header" role="banner">
      <div class="wrapper">
        <h1 class="header__title">User Overview</h1>
        <p class="header__subtitle">Profile &amp; signups...</p>
      </div>
    </header>
    <div className="container">
      <div className="wrapper">{children}</div>
    </div>
  </div>
);

export default UserShell;
