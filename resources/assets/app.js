import { ready } from './helpers';
import './app.scss';

import React from 'react';
import ReactDom from 'react-dom';

import CampaignOverview from './components/CampaignOverview'

ready(() => {
  const overviewContainer = document.getElementById('overviewContainer');

  if(overviewContainer)
  {
    ReactDom.render(<CampaignOverview />, overviewContainer);
  }
});
