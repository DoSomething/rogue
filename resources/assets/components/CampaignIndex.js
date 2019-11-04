import React, { useState } from 'react';

import CampaignsTable from './CampaignsTable';

const CampaignIndex = () => {
  const [showClosed, setShowClosed] = useState(false);
  const [filter, setFilter] = useState('');

  const onChange = event => {
    setFilter(event.target.value);
  };

  return (
    <>
      <header className="header" role="banner">
        <div className="wrapper">
          <h1 className="header__title">Campaigns</h1>
          <p className="header__subtitle">
            Reportback inbox &amp; management...
          </p>
        </div>
      </header>
      <div className="container">
        <div className="wrapper">
          <div className="container__block -half">
            <input
              type="text"
              className="text-field -search"
              placeholder="Filter by campaign ID, name, cause..."
              onChange={onChange}
            />
          </div>
          <div className="container__block -half form-actions -inline text-right">
            <a className="button -tertiary" href="/campaign-ids/create">
              New Campaign
            </a>
          </div>
          <div className="container__block">
            <h3>Open Campaigns</h3>
            <p>These campaigns are currently accepting new submissions.</p>
          </div>
          <div className="container__block">
            <CampaignsTable isOpen={true} filter={filter} />
          </div>
          {showClosed ? (
            <>
              <div className="container__block">
                <h3>Closed Campaigns</h3>
                <p>These campaigns are no longer accepting new submissions.</p>
              </div>
              <div className="container__block">
                <CampaignsTable isOpen={false} filter={filter} />
              </div>
            </>
          ) : (
            <div className="container__block form-actions">
              <button
                className="button -tertiary"
                onClick={() => setShowClosed(true)}
              >
                show closed campaigns
              </button>
            </div>
          )}
        </div>
      </div>
    </>
  );
};

export default CampaignIndex;
