/* global document */

import { isArray, mergeWith } from 'lodash';

/**
 * Valid statuses for posts, and their human-friendly labels.
 */
export const STATUSES = {
  accepted: 'Accepted',
  rejected: 'Rejected',
  pending: 'Pending',
};

/**
 * Valid tags for posts, and their human-friendly labels.
 */
export const TAGS = {
  'good-submission': 'Good Submission',
  'good-quote': 'Good Quote',
  'good-for-brand': 'Good For Brand',
  'good-for-sponsor': 'Good For Sponsor',
  'group-photo': 'Group Photo',
  'hide-in-gallery': 'Hide In Gallery 👻',
  irrelevant: 'Irrelevant',
  inappropriate: 'Inappropriate',
  'unrealistic-quantity': 'Unrealistic Quantity',
  test: 'Test',
  'incomplete-action': 'Incomplete Action',
  bulk: 'Bulk',
};

/**
 * Wait until the DOM is ready.
 *
 * @param {Function} fn
 */
export function ready(fn) {
  if (document.readyState !== 'loading') {
    fn();
  } else {
    document.addEventListener('DOMContentLoaded', fn);
  }
}

/**
 * Prefix a class name or array of class names.
 * @param {String|Array} names
 */
export function modifiers(...names) {
  let classes = names;

  if (!Array.isArray(classes)) {
    classes = [classes];
  }

  return classes
    .filter(className => className)
    .map(className => `-${className}`);
}

/*
 * Get a value from the environment.
 *
 * @param  {String} key
 * @return {String}
 */
export function env(key) {
  return (window.ENV || {})[key];
}

/**
 * Merge paginated GraphQL queries.
 *
 * @param {*} previous
 * @param {*} param1
 */
export const updateQuery = (previous, { fetchMoreResult }) => {
  return mergeWith({}, previous, fetchMoreResult, (dest, src) => {
    // By default, Lodash's `merge` would try to merge *each* array
    // item (e.g. `edges[0]` with then next page's `edges[0]`).
    if (isArray(dest)) {
      return [...dest, ...src];
    }
  });
};
