import React from 'react';
import MultiValueFilter from './index';
import { shallow } from 'enzyme';

test('it renders a list of tags', () => {
  const filters = {
    values: {
      'good-photo': {
        label: "Good Photo",
        active: false,
      },
      'good-quote': {
        label: "Good Quote",
        active: false,
      },
    },
    type: 'tags',
  };


  const component = shallow(
    <MultiValueFilter options={filters} header={'Tags'}/>
  );

  expect(component).toMatchSnapshot();
});

// test('it renders an active button when clicked', () => {
//   const filters = {
//     values: {
//       'good-photo': {
//         label: "Good Photo",
//         active: false,
//       },
//       'good-quote': {
//         label: "Good Quote",
//         active: false,
//       },
//     },
//     type: 'tags',
//   };

//   const component = shallow(
//     <MultiValueFilter options={filters} header={'Tags'}/>
//   );

//   component.find('button').simulate('click');

//   // @TODO: Make sure Dave wrote this assertion correctly!
//   expect(component.find('button').className.contains('is-active'));
// });
