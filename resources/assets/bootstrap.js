import './app.scss';

import React from 'react';
import ReactDom from 'react-dom';

import { ready } from './helpers';
import mountContainer from './utilities/MountContainer';

import Application from './Application';
import CampaignIdSingle from './components/CampaignIdSingle';

// Display environment badge on local, dev, or QA:
require('environment-badge')();

ready(() => {
  // For "modern" client-side rendered routes:
  if (document.getElementById('app')) {
    ReactDom.render(<Application />, document.getElementById('app'));
  }

  // For "legacy" pages that render using our custom helpers:
  mountContainer({
    CampaignIdSingle,
  });
});
