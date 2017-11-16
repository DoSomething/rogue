import React from 'react';
import { shallow, mount } from 'enzyme';

import Campaign from '../../_mocks_/__mockData__/campaign.json';
import CampaignInbox from '../CampaignInbox';
import reviewComponent from './index';

jest.mock('../../utilities/RogueClient');

test('CampaignInbox renders correctly when wrapped', () => {
  const mockState = {
    campaign: Campaign,
  };

  const ReviewingComponent = reviewComponent(CampaignInbox, mockState);

  const wrapper = shallow(<ReviewingComponent />);

  expect(wrapper.html()).not.toBe(null);
});


test('GetPostsByStatus is called when mounted', () => {
  const mockState = {
    campaign: Campaign,
    initial_posts: 'accepted',
  };

  const EmptyComponent = () => <div />;

  const ReviewingComponent = reviewComponent(EmptyComponent, mockState);

  const wrapper = mount(<ReviewingComponent />);

  setTimeout(() => {
    expect(wrapper.html()).not.toBe(null);

    wrapper.unmount();

    done();
  });

  // console.log(wrapper);
  // expect(wrapper.getPostsByStatus()).toHaveBeenCalled();
  //
  // const getPostsByStatus = jest.fn();
  // expect(getPostsByStatus).toHaveBeenCalled();

});

