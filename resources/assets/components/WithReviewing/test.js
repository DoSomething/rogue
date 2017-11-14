import React from 'react';
import { shallow } from 'enzyme';

import CampaignInbox from '../CampaignInbox';
import reviewComponent from './index';

test('CampaignInbox renders correctly', () => {
  const mockState = {
    campaign: {},
  };

  const ReviewingComponent = reviewComponent(CampaignInbox, mockState);

  const wrapper = shallow(<ReviewingComponent />);

  expect(wrapper.html()).not.toBe(null);
});

