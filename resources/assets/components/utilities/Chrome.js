import React from 'react';

const Chrome = ({ title, subtitle, children }) => (
  <div>
    <header className="header" role="banner">
      <div className="wrapper">
        <h1 className="header__title">{title}</h1>
        <p className="header__subtitle">{subtitle}</p>
      </div>
    </header>
    <div className="container">
      <div className="wrapper">{children}</div>
    </div>
  </div>
);

export default Chrome;
