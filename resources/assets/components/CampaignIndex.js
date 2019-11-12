import React, { useState } from 'react';

import Shell from './utilities/Shell';
import CampaignsTable from './CampaignsTable';

const CampaignIndex = () => {
  const [showClosed, setShowClosed] = useState(false);
  const [filter, setFilter] = useState('');

  return (
    <Shell title="Campaigns" subtitle="Reportback inbox & management...">
      <div className="container__block -half">
        <input
          type="text"
          className="text-field -search"
          placeholder="Filter by campaign ID, name, cause..."
          onChange={event => setFilter(event.target.value)}
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
    </Shell>
  );
};

export default CampaignIndex;
