import { ready } from './helpers';
import './app.scss';

import React from 'react';
import ReactDom from 'react-dom';
import mountContainer from './utilities/MountContainer';

import Signup from './components/Signup';
import UserOverview from './components/UserOverview';
import CampaignInbox from './components/CampaignInbox';
import CampaignSingle from './components/CampaignSingle';
import CampaignOverview from './components/CampaignOverview';

ready(() => {

  mountContainer({
    CampaignOverview: CampaignOverview,
    CampaignInbox: CampaignInbox,
  });

  const singleCampaignContainer = document.getElementById('singleCampaignContainer');
  const userOverviewContainer = document.getElementById('userOverviewContainer');
  const signupContainer = document.getElementById('signupContainer');

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
