import React from 'react';
import { shallow, mount } from 'enzyme';
import toJson from 'enzyme-to-json';

import ReviewBlock from './index';

test('it renders accepted, rejected, disabled tags, and delete buttons', () => {
  const post = {
    id: 1,
    tags: [],
    status: 'pending',
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

  const component = shallow(
    <ReviewBlock post={post} deletePost={deletePost} onUpdate={onUpdate} onTag={onTag} />,
  );

  expect(toJson(component)).toMatchSnapshot();
});

test('it renders an active Accept button when clicked', () => {
  const post = {
    status: 'pending',
    tags: [],
    id: 71,
  };

  const deletePost = function () {
    return true;
  };

  const onUpdate = jest.fn(() => Promise.resolve(true));

  const onTag = function () {
    return true;
  };

  const component = mount(
    <ReviewBlock post={post} deletePost={deletePost} onUpdate={onUpdate} onTag={onTag} />,
  );

  // Tags should be disabled.
  expect(component.find('.tag').first().is('[disabled]')).toBe(true);

  // Click the "Accept" button.
  component.find('StatusButton').findWhere(n => n.prop('type') === 'accepted').simulate('click');
  expect(onUpdate).toHaveBeenCalled();

  // The "Accept" button should be "loading".
  expect(component.find('StatusButton').findWhere(n => n.prop('type') === 'accepted').first().hasClass('button -accepted is-loading'));

  // The "Reject" button should not be "loading".
  expect(component.find('StatusButton').findWhere(n => n.prop('type') === 'rejected').first().hasClass('button -outlined-button -rejected'));
});

test('it renders an active Reject button when clicked', () => {
  const post = {
    status: 'pending',
    tags: [],
    id: 71,
  };

  const deletePost = function () {
    return true;
  };

  const onUpdate = jest.fn(() => Promise.resolve(true));

  const onTag = function () {
    return true;
  };

  const component = mount(
    <ReviewBlock post={post} deletePost={deletePost} onUpdate={onUpdate} onTag={onTag} />,
  );

  // Tags should be disabled.
  expect(component.find('.tag').first().is('[disabled]')).toBe(true);

  // Click the "Reject" button.
  component.find('StatusButton').findWhere(n => n.prop('type') === 'rejected').simulate('click');
  expect(onUpdate).toHaveBeenCalled();

  // The "Reject" button should be "loading".
  expect(component.find('StatusButton').findWhere(n => n.prop('type') === 'accepted').first().hasClass('button -rejected is-loading'));

  // The "Accept" button should not be "loading".
  expect(component.find('StatusButton').findWhere(n => n.prop('type') === 'rejected').first().hasClass('button -outlined-button -accepted'));
});
