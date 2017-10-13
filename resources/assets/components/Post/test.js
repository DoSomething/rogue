import React from 'react';
import renderer from 'react-test-renderer';

import Post from './index';

it('renders correctly', () => {
  const post = {
    caption: 'Here is my awesome caption!',
  };

  const signup = {
    signup_id: 7,
  }

  const tree = renderer.create(
    <Post post={post} signup={signup}></Post>
  ).toJSON();
  expect(tree).toMatchSnapshot();
});
