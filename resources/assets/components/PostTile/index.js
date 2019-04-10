import React from 'react';
import PropTypes from 'prop-types';
import { getImageUrlFromProp } from '../../helpers';

import './post-tile.scss';

const PostTile = ({ post }) => {
  const url = getImageUrlFromProp(post, 'edited');
  const altText = `Post #${post.id}`;

  if (!url) {
    return <div className="post-tile -fallback" />;
  }

  return <img className="post-tile" alt={altText} src={url} />;
};

PostTile.propTypes = {
  post: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
};

export default PostTile;
