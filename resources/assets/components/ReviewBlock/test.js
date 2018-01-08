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
  expect(component.find('.disabled').first().text()).toBe('Tags');
  // expect(component.find('.tag').first().is('[disabled]')).toBe(true);

  // Click the "Accept" button.
  component.find('StatusButton').findWhere(n => n.prop('type') === 'accepted').simulate('click');
  expect(onUpdate).toHaveBeenCalled();

  component.update();
  console.log(component.debug());

  // It should now show the active "Accept" button.
  expect(component.find('StatusButton').findWhere(n => n.prop('type') === 'accepted').first().hasClass('button -outlined-button -accepted is-selected'));
  // const acceptButton = component.find('StatusButton').findWhere(n => n.prop('type') === 'accepted').first();
  // expect(acceptButton.props().status).to.equal('accepted');
  // expect(component.find('.accepted').first().hasClass('button -outlined-button -accepted is-selected'));

  // Tags should now be enabled.
  // expect(component.find('.tag').first().is('[disabled]')).toBe(false);
  // expect(component.find('.enabled').first().text()).toBe('Tags');
});

// test('it renders an active Reject button when clicked', () => {
//   const post = {
//     status: 'pending',
//     tags: [],
//     id: 71,
//   };

//   const deletePost = function () {
//     return true;
//   };

//   const onUpdate = function () {
//     return true;
//   };

//   const onTag = function () {
//     return true;
//   };

//   const component = mount(
//     <ReviewBlock post={post} deletePost={deletePost} onUpdate={onUpdate} onTag={onTag} />,
//   );

//   // Click the "Reject" button.
//   component.find('.rejected').first().simulate('click');

//   // It should now show the active "Reject" button.
//   expect(component.find('.rejected').first().hasClass('button -outlined-button -rejected is-selected'));
// });

// test('it renders an active tag button when clicked', () => {
//   const post = {
//     status: 'accepted',
//     tags: [],
//     id: 71,
//   };

//   const deletePost = function () {
//     return true;
//   };

//   const onUpdate = function () {
//     return true;
//   };

//   const onTag = function () {
//     return true;
//   };

//   const component = mount(
//     <ReviewBlock post={post} deletePost={deletePost} onUpdate={onUpdate} onTag={onTag} />,
//   );

//   // Click a tag button.
//   component.find('.tag').first().simulate('click');

//   // It should now show the active tag button.
//   expect(component.find('.tag').first().hasClass('is-active'));
// });

