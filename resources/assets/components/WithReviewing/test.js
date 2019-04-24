import React from 'react';
import { mount } from 'enzyme';
import reviewComponent from './index';

// Mock data.
import Campaign from '../../_mocks_/__mockData__/campaign.json';
import Posts from '../../_mocks_/__mockData__/posts.json';
import Events from '../../_mocks_/__mockData__/events.json';

jest.mock('../../utilities/RogueClient');

// Mock the 'window.AUTH' object:
window.AUTH = {
  id: '5571f4f5a59dbf3c7a8b4569',
  token: 'eyJ0eXAiOiJKV1QiL…iAYvaQ6WjXLRDn-ybli4tynNeDarwaB8YviUJfRT1B2XHutB8',
  role: 'admin',
};

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

  it('Renders the MockReviewingComponent as the root element', () => {
    expect(Wrapper.first().is(ReviewingComponent)).toBeTruthy();
  });

  it('It populates state to have campaign and post data after mounted', () => {
    const state = Wrapper.state();

    // Make sure campaign data is correct.
    expect(state.campaign.data).toEqual(Campaign.data);

    // Make sure post data is correct.
    expect(state.posts['70']).toEqual(Posts.data[0]);
    expect(state.posts['71']).toEqual(Posts.data[1]);
  });

  // @TODO: update the below to make sure it is compatible with update /posts/{post}/reviews endpoint
  // it('setNewPosts changes state correctly', () => {
  //   const instance = Wrapper.instance();

  //   // Remove an element from the posts array and update state with it.
  //   Posts.data.pop();
  //   instance.setNewPosts(Posts);

  //   const state = Wrapper.state();

  //   // Make sure campaign data is correct.
  //   expect(state.campaign.data).toEqual(Campaign.data);

  //   // Make sure post data is correct.
  //   expect(state.posts['70']).toEqual(Posts.data[0]);
  //   expect(state.posts['71']).toBeUndefined();
  // });

  // it('test showHistory updates state properly', (done) => {
  //   const instance = Wrapper.instance();
  //   const postId = 70;
  //   const signupId = 19;
  //   const mockedEvent = { preventDefault: () => false };

  //   // Call ShowHistory
  //   instance.showHistory(postId, mockedEvent, signupId);

  //   // Use Node's setImmediate to defer the
  //   // test until the promise in showHistory resolves.
  //   setImmediate(() => {
  //     expect(Wrapper.state('displayHistoryModal')).toBe(true);
  //     expect(Wrapper.state('historyModalId')).toEqual(70);
  //     expect(Wrapper.state('signupEvents')).toEqual(Events.data);

  //     done();
  //   });
  // });

  // it('hideHistory updates state properly', () => {
  //   const instance = Wrapper.instance();
  //   const mockedEvent = { preventDefault: () => false };

  //   // Call ShowHistory
  //   instance.hideHistory(mockedEvent);

  //   expect(Wrapper.state('displayHistoryModal')).toBe(false);
  //   expect(Wrapper.state('historyModalId')).toBeNull();
  // });

  // it('updatePost', () => {
  //   const instance = Wrapper.instance();

  //   return instance.updatePost(70, { status: 'rejected' }).then(() => {
  //     const posts = Wrapper.state('posts');

  //     expect(posts['70'].status).toEqual('rejected');
  //   });
  // });
});
