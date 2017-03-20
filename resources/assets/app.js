import { ready } from './helpers';
import './app.scss';

import React from 'react';
import ReactDom from 'react-dom';

import CampaignOverview from './components/CampaignOverview'
import CampaignInbox from './components/CampaignInbox'

ready(() => {
  const overviewContainer = document.getElementById('overviewContainer');
  const inboxContainer = document.getElementById('inboxContainer');


  if (overviewContainer) {
    ReactDom.render(<CampaignOverview {...window.STATE} />, overviewContainer);
  }

  if (inboxContainer) {
    ReactDom.render(<CampaignInbox {...window.STATE} />, inboxContainer);
  }
});
