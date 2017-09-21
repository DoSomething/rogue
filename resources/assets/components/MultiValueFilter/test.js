import React from 'react';
import { mount } from 'enzyme';
import toJson from 'enzyme-to-json';
import sinon from 'sinon';

import MultiValueFilter from './index';

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


  const component = mount(
    <MultiValueFilter options={filters} header={'Tags'} updateFilters={() => {}}/>
  );

  expect(toJson(component)).toMatchSnapshot();
});

test('it renders an active button when clicked', () => {
  var callback = sinon.spy();
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

  const component = mount(
    <MultiValueFilter options={filters} header="Tags" updateFilters={callback}/>
  );

  // Click the first "tag" button in the filter.
  component.find('button').first().simulate('click');

  // It should now show that tag as selected, & parent component should be
  // notified via the `updateFilters` prop callback.
  expect(component.find('button').first().hasClass('is-active'));
  expect(callback.calledOnce);

});
