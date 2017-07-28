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
	  return "https://www.dosomething.org/sites/default/files/JenBugError.png";
	}
	else {
	  return photo_url;
	}
};

export function extractPostsFromSignups(signups) {
    const posts = flatMap(signups, signup => {
      return signup.posts;
    });

    return posts;
}

export function getEditedImageUrl(photoProp) {
  const edited_file_name = `edited_${photoProp.id}.jpeg`;
  const url_parts = photoProp['url'].split("/");

  url_parts.pop();
  url_parts.push(edited_file_name);

  return url_parts.join('/');
};
