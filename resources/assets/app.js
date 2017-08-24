import { ready } from './helpers';
import './app.scss';

import React from 'react';
import ReactDom from 'react-dom';

import CampaignOverview from './components/CampaignOverview';
import CampaignInbox from './components/CampaignInbox';
import CampaignSingle from './components/CampaignSingle';
import Signup from './components/Signup';
import UserOverview from './components/UserOverview';

ready(() => {
  const overviewContainer = document.getElementById('overviewContainer');
  const inboxContainer = document.getElementById('inboxContainer');
  const singleCampaignContainer = document.getElementById('singleCampaignContainer');
  const userOverviewContainer = document.getElementById('userOverviewContainer');
  const signupContainer = document.getElementById('signupContainer');

  if (overviewContainer) {
    ReactDom.render(<CampaignOverview {...window.STATE} />, overviewContainer);
  }

  if (inboxContainer) {
    ReactDom.render(<CampaignInbox {...window.STATE} />, inboxContainer);
  }

  if (singleCampaignContainer) {
    ReactDom.render(<CampaignSingle {...window.STATE} historyModalId={null} />, singleCampaignContainer);
  }

  if (userOverviewContainer) {
    ReactDom.render(<UserOverview {...window.STATE} />, userOverviewContainer);
  }

  if (signupContainer) {
    ReactDom.render(<Signup {...window.STATE} />, signupContainer);
  }
});
