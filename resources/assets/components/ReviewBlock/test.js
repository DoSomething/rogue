import React from 'react';
import { mount, shallow } from 'enzyme';
import toJson from 'enzyme-to-json';

import ReviewBlock from './index';

test('it renders the review block', () => {
    const post = {
      status: 'accepted',
    };

  const component = shallow(
    <ReviewBlock post={post} />
  );

  expect(toJson(component)).toMatchSnapshot();
});
