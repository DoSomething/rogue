import React from 'react';
import { handleClick } from '../MultiValueFilter';

test('handleClick changes button to active', () => {
  const startState = {
    tags: {
      'good-photo': {
        active: false,
        label: 'Good Photo',
      }
    }
  };
  const finState = handleClick('good-photo', false, 'tags');

  expect(finState.tags['good-photo']).toEqual([
    { active: true, label: 'Good Photo' }
  ]);
});
