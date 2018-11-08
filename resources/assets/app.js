import 'babel-polyfill';

import { ready } from './helpers';
import './app.scss';

import mountContainer from './utilities/MountContainer';

import Signup from './components/Signup';
import UserOverview from './components/UserOverview';
import CampaignInbox from './components/CampaignInbox';
import CampaignSingle from './components/CampaignSingle';
import CampaignOverview from './components/CampaignOverview';

// Display environment badge on local, dev, or QA:
require('environment-badge')();

ready(() => {
  mountContainer({
    CampaignOverview,
    CampaignInbox,
    CampaignSingle,
    UserOverview,
    Signup,
  });
});
