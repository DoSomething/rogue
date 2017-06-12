import { ready } from './helpers';
import './app.scss';

import React from 'react';
import ReactDom from 'react-dom';

import CampaignOverview from './components/CampaignOverview';
import CampaignInbox from './components/CampaignInbox';
import CampaignSingle from './components/CampaignSingle';
// import PostFilter from './components/PostFilter';
// import StatusCounter from './components/StatusCounter';

ready(() => {
  const overviewContainer = document.getElementById('overviewContainer');
  const inboxContainer = document.getElementById('inboxContainer');
  const singleCampaignContainer = document.getElementById('singleCampaignContainer');

  if (overviewContainer) {
    ReactDom.render(<CampaignOverview {...window.STATE} />, overviewContainer);
  }

  if (inboxContainer) {
    ReactDom.render(<CampaignInbox {...window.STATE} />, inboxContainer);
  }

  if (singleCampaignContainer) {
    // ReactDom.render(<StatusCounter {...window.STATE} />, document.getElementById('status-counter'));
    // ReactDom.render(<PostFilter {...window.STATE} />, document.getElementById('status-filter'));
    ReactDom.render(<CampaignSingle{...window.STATE} />, document.getElementById('singleCampaignContainer'));
  }
});
