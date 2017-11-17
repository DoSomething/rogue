import React from 'react';
import { mount } from 'enzyme';
import Campaign from '../../_mocks_/__mockData__/campaign.json';
import Posts from '../../_mocks_/__mockData__/posts.json';
import reviewComponent from './index';

jest.mock('../../utilities/RogueClient');

describe('WithReviewing', () => {
  let Wrapper;
  let ReviewingComponent;

  beforeEach(() => {
    const mockState = {
      campaign: Campaign,
      initial_posts: 'accepted',
    };

    const EmptyComponent = () => <div />;

    ReviewingComponent = reviewComponent(EmptyComponent, mockState);

    Wrapper = mount(<ReviewingComponent />);
  });

  it('renders the MockReviewingComponent as the root element', () => {
    expect(Wrapper.first().is(ReviewingComponent)).toBeTruthy();
  });

  it('It populates state to have campaign and post data after mounted', () => {
    // console.log(Wrapper.state());
    const state = Wrapper.state();
    // const postCount = Object.keys(state.posts).length;

    // Make sure campaign data is correct.
    expect(state.campaign.data).toEqual(Campaign.data);

    // Make sure post data is correct.
    expect(state.posts['70']).toEqual(Posts.data[0]);
    expect(state.posts['71']).toEqual(Posts.data[1]);
  });
});
