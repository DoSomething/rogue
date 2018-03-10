import { ready } from './helpers';
import './app.scss';

import React from 'react';
import ReactDom from 'react-dom';
import mountContainer from './utilities/MountContainer';
import 'babel-polyfill';

import Signup from './components/Signup';
import UserOverview from './components/UserOverview';
import CampaignInbox from './components/CampaignInbox';
import CampaignSingle from './components/CampaignSingle';
import CampaignOverview from './components/CampaignOverview';

ready(() => {
  mountContainer({
    CampaignOverview,
    CampaignInbox,
    CampaignSingle,
    UserOverview,
    Signup,
  });
});
