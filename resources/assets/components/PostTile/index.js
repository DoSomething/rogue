import React from 'react';
import PropTypes from 'prop-types';
import { getImageUrlFromProp } from '../../helpers';

class PostTile extends React.Component {
  render() {
    const post = this.props.details;

    return (
      <li>
        <img src={getImageUrlFromProp(post) || post.media.url} />
      </li>
    );
  }
}

PostTile.propTypes = {
  details: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
};

export default PostTile;
