import React from 'react';
import renderer from 'react-test-renderer';

import Post from './index';

test('it renders correctly', () => {
  const post = {
    id: 123,
    caption: 'Here is my awesome caption!',
    status: 'pending',
    tags: [],
  };

  const signup = {
    signup_id: 7,
  };

  const deletePost = function () {
    return true;
  };

  const onUpdate = function () {
    return true;
  };

  const onTag = function () {
    return true;
  };

  const tree = renderer.create(
    <Post post={post} signup={signup} deletePost={deletePost} onUpdate={onUpdate} onTag={onTag} />,
  ).toJSON();
  expect(tree).toMatchSnapshot();
});
