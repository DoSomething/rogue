import React from 'react';
import PropTypes from 'prop-types';
import { getImageUrlFromProp } from '../../helpers';

import './post-tile.scss';

const PostTile = ({ details }) => {
  const url = getImageUrlFromProp(details, 'edited');
  const altText = `Post #${details.id}`;

  return (
    <li>
      <img className="post-tile" alt={altText} src={url} />
    </li>
  );
};

PostTile.propTypes = {
  details: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
};

export default PostTile;
