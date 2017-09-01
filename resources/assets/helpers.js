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
	var photo_url;

  // Sometimes we get the url right on the post and sometimes it is nested under
  // media (in cases where it goes through the PostTransformer), so handle both cases
  // @TODO: make sure everything goes through a transformer so we don't need this
  if ('url' in photoProp) {
    photo_url = photoProp['url'];
  }
  else if ('media' in photoProp) {
    photo_url = photoProp['media']['original_image_url'];
  }


  if (photo_url == "default") {
    return "https://www.dosomething.org/sites/default/files/JenBugError.png";
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

export function getEditedImageUrl(photoProp) {
  const edited_file_name = `edited_${photoProp.id}.jpeg`;
  var url_parts;

  // Sometimes we get the url right on the post and sometimes it is nested under
  // media (in cases where it goes through the PostTransformer), so handle both cases
  if ('url' in photoProp) {
    url_parts = photoProp['url'].split("/");
    url_parts.pop();
    url_parts.push(edited_file_name);

    return url_parts.join('/');
  }
  else if ('media' in photoProp) {
    return photoProp['media']['url'];
  }

  return null;
};

/**
 * Returns a readable display name.
 *
 * @param {String} firstName
 * @param {String} lastName
 */
export function displayName(firstName, lastName) {
  let displayName = firstName;

  if (lastName) {
    displayName = `${displayName} ${lastName}`;
  }

  return displayName;
}

/**
 * Returns a readable City and State string.
 *
 * @param {String} city
 * @param {String} state
 * @return {String|null} City and State string.
 */
export function displayCityState(city, state) {
  if (!city && !state) {
    return null;
  }

  return `${city ? city : ''}${city && state ? ', ' : ''}${state ? state : ''}`
}


/**
 * Returns a readable caption string.
 *
 * @param {Array} post
 * @return {String|null} caption string.
 */
export function displayCaption(post) {
  if (post['caption']) {
    return post['caption'];
  } else if (post.media) {
    return post.media['caption'];
  }

  return null;
}

/**
 * Process file (provided as an ArrayBuffer) depending
 * on its type.
 *
 * @param  {ArrayBuffer} file
 * @return {Blob}
 * @todo Eventually deal with other file types.
 */
export function processFile(file) {
  const fileType = getFileType(file);
  const dataView = new DataView(file);

  if (fileType === 'image/png') {
    return new Blob([dataView], { type: fileType });
  }

  if (fileType === 'image/jpeg') {
    return stripExifData(file, dataView);
  }

  throw new Error('Unsupported file type.');
}

/**
 * Make a hash from a specified string.
 * @see  http://werxltd.com/wp/2010/05/13/javascript-implementation-of-javas-string-hashcode-method/
 *
 * @param  {String} string
 * @return {String}
 */
export function makeHash(string) {
  if (string === undefined || string === null) {
    throw new Error('Cannot make hash from undefined or null value.');
  }

  let hash = 0;

  if (! string.length) {
    return hash;
  }

  string.split('').forEach((char, index) => {
    const charCode = string.charCodeAt(index);
    hash = ((hash << 5) - hash) + charCode; // eslint-disable-line no-bitwise
    hash = hash & hash; // eslint-disable-line no-bitwise, operator-assignment
  });

  return Math.abs(hash);
}

/**
 * Prefix a class name or array of class names.
 * @param {String|Array} names
 */
export function modifiers(...names) {
  let classes = names;

  if (! Array.isArray(classes)) {
    classes = [classes];
  }

  return classes.filter(className => className).map(className => `-${className}`);
}
