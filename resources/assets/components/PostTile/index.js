import React from 'react';
import { getImageUrlFromProp } from '../../helpers';

class PostTile extends React.Component {
  render() {
    const post = this.props.details;

    return (
      <li>
        <img src={getImageUrlFromProp(post) || post.media['url']}/>
      </li>
    )
  }
}

export default PostTile;
