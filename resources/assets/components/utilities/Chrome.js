import React from 'react';

const BaseChrome = ({ title, subtitle, children }) => (
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

const Chrome = ({ title, subtitle, children, error, loading }) => {
  if (error) {
    return (
      <BaseChrome title="Oops!" subtitle="Something went wrong...">
        <strong>Error:</strong> {error}
      </BaseChrome>
    );
  }

  if (loading) {
    return (
      <BaseChrome title={title || 'Loading...'} subtitle={subtitle || '...'}>
        <div className="placeholder flex-center-xy">
          <div className="spinner" />
        </div>
      </BaseChrome>
    );
  }

  return (
    <BaseChrome title={title} subtitle={subtitle}>
      {children}
    </BaseChrome>
  );
};

export default Chrome;
