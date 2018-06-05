/**
 * Wait until the DOM is ready.
 *
 * @param {Function} fn
 */
import { flatMap, keyBy, get } from 'lodash';

export function ready(fn) {
  if (document.readyState !== 'loading') {
    fn();
  } else {
    document.addEventListener('DOMContentLoaded', fn);
  }
}

export function calculateAge(date) {
  let formattedAge = null;

  if (date) {
    const birthdate = new Date(date);
    const today = Date.now();
    formattedAge = Math.floor((today - birthdate) / 31536000000);
  }

  return formattedAge;
}

export function extractPostsFromSignups(signups) {
  const posts = keyBy(flatMap(signups, signup => signup.posts), 'id');

  return posts;
}

export function extractSignupsFromPosts(posts) {
  const signups = keyBy(flatMap(posts, post => post.signup.data), 'id');

  return signups;
}

/**
 * Returns a readable display name and age (if provided).
 *
 * @param {String} firstName
 * @param {String} lastName
 * @param {Date} birthDate
 */
export function displayUserInfo(firstName, lastName, birthDate) {
  let displayName = firstName;
  if (lastName) {
    displayName = `${displayName} ${lastName}`;
  }

  if (birthDate) {
    const age = calculateAge(birthDate);
    return `${displayName}, ${age}`;
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

  return `${city || ''}${city && state ? ', ' : ''}${state || ''}`;
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

export function processFile64(file) {
  const fileType = getFileType(file);
  const dataView = new DataView(file);

  if (fileType === 'image/png') {
    return new Blob([dataView], { type: fileType });
  }

  if (fileType === 'image/jpeg') {
    return new File([dataView], { type: fileType });
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

  if (!string.length) {
    return hash;
  }

  string.split('').forEach((char, index) => {
    const charCode = string.charCodeAt(index);
    hash = (hash << 5) - hash + charCode; // eslint-disable-line no-bitwise
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

  if (!Array.isArray(classes)) {
    classes = [classes];
  }

  return classes
    .filter(className => className)
    .map(className => `-${className}`);
}

/**
 * Get the type for a specified file.
 *
 * @param  {ArrayBuffer} file
 * @return {String|null}
 * @todo Eventually deal with other file types.
 */
function getFileType(file) {
  const dv = new DataView(file, 0, 5);
  const byte1 = dv.getUint8(0, true);
  const byte2 = dv.getUint8(1, true);
  const hex = byte1.toString(16) + byte2.toString(16);

  return get(
    {
      '8950': 'image/png', // eslint-disable-line quote-props
      '4749': 'image/gif', // eslint-disable-line quote-props
      '424d': 'image/bmp', // eslint-disable-line quote-props
      ffd8: 'image/jpeg', // eslint-disable-line quote-props
    },
    hex,
    null,
  );
}

/**
 * Remove EXIF data on specified file if present.
 *
 * @param  {ArrayBuffer} image
 * @param  {DataView} dv
 * @return {Blob}
 */
function stripExifData(image, dv = null) {
  let dataView = dv;

  if (!dataView) {
    dataView = new DataView(image);
  }

  const pieces = [];
  let offset = 0;
  let recess = 0;
  let i = 0;

  offset += 2;
  let app1 = dataView.getUint16(offset);
  offset += 2;

  // This loop does the acutal reading of the data and creates
  // an array with only the pieces we want.
  while (offset < dataView.byteLength) {
    if (app1 === 0xffe1) {
      pieces[i] = {
        recess,
        offset: offset - 2,
      };

      recess = offset + dataView.getUint16(offset);

      i += 1;
    } else if (app1 === 0xffda) {
      break;
    }

    offset += dataView.getUint16(offset);
    app1 = dataView.getUint16(offset);
    offset += 2;
  }

  // If the file had EXIF data and it was removed, create a
  // file blob using the new array of file data.
  if (pieces.length > 0) {
    const newPieces = [];

    pieces.forEach(piece => {
      newPieces.push(image.slice(piece.recess, piece.offset));
    }, this);

    newPieces.push(image.slice(recess));

    return new Blob(newPieces, { type: 'image/jpeg' });
  }

  // If no EXIF data existed on the file, then nothing was done to it.
  // We can just create a blob with the original data.
  return new Blob([dataView], { type: 'image/jpeg' });
}

// DEPRECATED: Uses getImageUrlFromPost() instead.
export function getImageUrlFromProp(photoProp) {
  let photo_url;

  // Sometimes we get the url right on the post and sometimes it is nested under
  // media (in cases where it goes through the PostTransformer), so handle both cases
  // @TODO: make sure everything goes through a transformer so we don't need this
  if ('url' in photoProp) {
    photo_url = photoProp.url;
  } else if ('media' in photoProp) {
    photo_url = photoProp.media.original_image_url;
  }

  if (photo_url == 'default' || photo_url == null) {
    return 'https://www.dosomething.org/sites/default/files/JenBugError.png';
  }

  return photo_url;
}

// DEPRECATED: Uses getImageUrlFromPost() instead.
export function getEditedImageUrl(photoProp) {
  const edited_file_name = `edited_${photoProp.id}.jpeg`;
  let url_parts;

  // Sometimes we get the url right on the post and sometimes it is nested under
  // media (in cases where it goes through the PostTransformer), so handle both cases
  if ('url' in photoProp) {
    url_parts = photoProp.url.split('/');
    url_parts.pop();
    url_parts.push(edited_file_name);

    return url_parts.join('/');
  } else if ('media' in photoProp) {
    return photoProp.media.url;
  }

  return null;
}

/*
 * Given a transformed post object, return the url based on type.
 *
 * @param  {Object} post
 * @return {String|null}
 * @todo Eventually deal with other file types.
 */
export function getImageUrlFromPost(post, type) {
  let url = null;
  const defaultPhotoUrl =
    'https://www.dosomething.org/sites/default/files/JenBugError.png';

  // Make sure media property is included in the Post.
  if (!('media' in post)) {
    return url;
  }

  switch (type) {
    case 'original':
      url = post.media.original_image_url;
      break;
    case 'edited':
      url = post.media.url;
      break;
    default:
      break;
  }

  return url === 'default' ? defaultPhotoUrl : url;
}
