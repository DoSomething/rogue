import React from 'react';
import { shallow } from 'enzyme';
import toJson from 'enzyme-to-json';

import ReviewBlock from './index';

test('it renders accepted, rejected, and delete buttons', () => {
  const post = {
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

test('it renders the review block with tags', () => {
  const post = {
    status: 'accepted',
    tags: [],
    id: 71,
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
    // <ReviewBlock post={post} deletePost={deletePost} onUpdate={onUpdate} />,
  );

  expect(toJson(component)).toMatchSnapshot();
});

test('it renders an active Accept button when clicked', () => {
  const post = {
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

  // Click the "Accept" button.
  component.find('.accepted').first().simulate('click');

  // It should now show the active "Accept" button.
  expect(component.find('.accepted').first().hasClass('button -outlined-button -accepted is-selected'));
});

test('it renders an active Reject button when clicked', () => {
  const post = {
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

  // Click the "Reject" button.
  component.find('.rejected').first().simulate('click');

  // It should now show the active "Reject" button.
  expect(component.find('.rejected').first().hasClass('button -outlined-button -rejected is-selected'));
});

test('it renders an active tag button when clicked', () => {
  const post = {
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

  // Click a tag button.
  component.find('.tag').first().simulate('click');

  // It should now show the active "Reject" button.
  expect(component.find('.tag').first().hasClass('is-active'));
});

