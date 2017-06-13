/**
 * Wait until the DOM is ready.
 *
 * @param {Function} fn
 */
import { flatMap, keyBy } from 'lodash';

export function ready(fn) {
  if (document.readyState !== 'loading'){
    fn();
  } else {
    document.addEventListener('DOMContentLoaded', fn);
  }
}

export function calculateAge(date) {
	const birthdate = new Date(date);
	const today = Date.now();
	const age = Math.floor((today - birthdate) / 31536000000);

	return age;
};

export function getImageUrlFromProp(photoProp) {
  const photo_url = photoProp['url'];

	if (photo_url == "default") {
	  return "https://pics.onsizzle.com/bork-2411135.png";
	}
	else {
	  return photo_url;
	}
};

export function extractPostsFromSignups(signups) {
    const posts = keyBy(flatMap(signups, signup => {
      return signup.posts;
    }), 'id');

    return posts;
}
