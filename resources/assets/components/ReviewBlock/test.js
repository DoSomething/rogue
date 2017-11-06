import React from 'react';
import { shallow } from 'enzyme';
import toJson from 'enzyme-to-json';

import ReviewBlock from './index';

test('it renders accepted, rejected, and delete buttons', () => {
  const post = {
    status: 'pending',
  };

  const component = shallow(
    <ReviewBlock post={post} />,
  );

  expect(toJson(component)).toMatchSnapshot();
});

test('it renders the review block with tags', () => {
  const post = {
    status: 'accepted',
  };

  const component = shallow(
    <ReviewBlock post={post} />,
  );

  expect(toJson(component)).toMatchSnapshot();
});

test('it renders an active Accept button when clicked', () => {
  const post = {
    status: 'pending',
  };

  const component = shallow(
    <ReviewBlock post={post} />,
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

  const component = shallow(
    <ReviewBlock post={post} />,
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

  const component = shallow(
    <ReviewBlock post={post} />,
  );

  // Click a tag button.
  component.find('.tag').first().simulate('click');

  // It should now show the active "Reject" button.
  expect(component.find('.tag').first().hasClass('is-active'));
});

