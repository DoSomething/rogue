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
    CampaignSingle: CampaignSingle,
    UserOverview: UserOverview,
  });

  const signupContainer = document.getElementById('signupContainer');

  if (signupContainer) {
    ReactDom.render(<Signup {...window.STATE} />, signupContainer);
  }
});
