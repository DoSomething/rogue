import { ready } from './helpers';
import './app.scss';

import React from 'react';
import ReactDom from 'react-dom';

import CampaignTable from './components/CampaignTable'

ready(() => {
  const overviewContainer = document.getElementById('overviewContainer');

  if(overviewContainer)
  {
    ReactDom.render(<CampaignTable />, overviewContainer);
  }
});
