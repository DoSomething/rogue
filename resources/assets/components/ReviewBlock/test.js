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

  const onUpdate = function () {
    return true;
  };

  const onTag = function () {
    return true;
  };

  const component = mount(
    <ReviewBlock post={post} deletePost={deletePost} onUpdate={onUpdate} onTag={onTag} />,
  );

  // Tags should be disabled.
  // expect(component.find('.tag').first().is('[disabled]')).toBe(true);
  // expect(component.find('.tag').first().disabled).toBe(true);
  // expect(component.find('.tag').first()).toBe('disabled');
  // expect(component.find('.disabled').first().toBe(true));
  // expect(component.find('.tag.disabled').first().toBe(true));
  // const disabledTag = component.find('.tag').first();
  // expect(disabledTag.disabled).toEqual(true);
  // expect(component.find('.disabled').first()).toBe('Tags');
  // expect(component.find('.disabled').first()).toEqual('Tags');
  // expect(component.find('.disabled').to.be('Tags'));
  // expect(component.find('.disabled').toEqual('Tags'));
  // expect(component.find('.disabled').first().text()).toBe('Tags');
  // expect(component.find('.disabled').first().text()).toEqual('Tags');
  // const disabledTag = component.find('.tag Tag').first();
  // disabledTag.simulate('click');
  // component.find('.tag').first().simulate('click');
  // expect(disabledTag).toEqual(true);

  // Click the "Accept" button and mock the handleClick function from the StatusButton component.
  const mockUpdate = jest.fn(() => Promise.resolve(true));
  const wrapper = mount(<ReviewBlock onUpdate={mockUpdate} />);
  // …simulate the click
  component.find('StatusButton').findWhere(n => n.prop('type') === 'accepted').simulate('click');
  expect(mockFn).toHaveBeenCalled();
  // component.findWhere(n => n.name() === 'StatusButton' && n.prop('type') === 'accepted').simulate('click');

  // It should now show the active "Accept" button.
  expect(component.find('StatusButton').findWhere(n => n.prop('type') === 'accepted').first().hasClass('button -outlined-button -accepted is-selected'));

  // Tags should now be enabled.
  // const enabledTag = component.find('.tag').first();
  // enabledTag.simulate('click');
  // expect(enabledTag).toEqual(true);
  // expect(enabled.enabled).toEqual(true);
  // expect(component.find('.tag').first().is('[enabled]')).toBe(true);

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

