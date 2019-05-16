import { ready } from './helpers';
import './app.scss';

import mountContainer from './utilities/MountContainer';

import Signup from './components/Signup';
import UserOverview from './components/UserOverview';
import CampaignInbox from './components/CampaignInbox';
import CampaignSingle from './components/CampaignSingle';
import CampaignIdSingle from './components/CampaignIdSingle';
import CampaignOverview from './components/CampaignOverview';
import CampaignIdOverview from './components/CampaignIdOverview';

// Display environment badge on local, dev, or QA:
require('environment-badge')();

ready(() => {
  mountContainer({
    CampaignOverview,
    CampaignIdOverview,
    CampaignInbox,
    CampaignIdSingle,
    CampaignSingle,
    UserOverview,
    Signup,
  });
});
