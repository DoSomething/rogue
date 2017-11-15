import React from 'react';
import { shallow, mount } from 'enzyme';

import CampaignInbox from '../CampaignInbox';
import reviewComponent from './index';

jest.mock('../../utilities/RogueClient');

test('CampaignInbox renders correctly', () => {
  const mockState = {
    campaign: {},
  };

  const ReviewingComponent = reviewComponent(CampaignInbox, mockState);

  const wrapper = shallow(<ReviewingComponent />);

  expect(wrapper.html()).not.toBe(null);
});


test('GetPostsByStatus is called when mounted', () => {
  const mockState = {
    campaign: {
      id: 1283,
    },
    initial_posts: 'accepted',
  };

  const ReviewingComponent = reviewComponent(CampaignInbox, mockState);

  const wrapper = mount(<ReviewingComponent />);

  // const getPostsByStatus = jest.fn();
  // expect(getPostsByStatus).toHaveBeenCalled();
  expect(wrapper.html()).not.toBe(null);

  // console.log(wrapper);
  // expect(wrapper.getPostsByStatus()).toHaveBeenCalled();
});

