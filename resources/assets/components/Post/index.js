import React from 'react';
import { remove, map, clone } from 'lodash';
import { getImageUrlFromProp, getEditedImageUrl, displayCaption } from '../../helpers';

import Tags from '../Tags';
import StatusButton from '../StatusButton';

class Post extends React.Component {
  render() {
    const post = this.props.post;
    const caption = displayCaption(post);

    return (
      <div className="container__row">
        <div className="container__block -third">
          <img src={getImageUrlFromProp(post)}/>
          <p>
            <a href={getImageUrlFromProp(post)} target="_blank">Original Photo</a> | <a href={getEditedImageUrl(post)} target="_blank">Edited Photo</a>
          </p>
        </div>
        <div className="container__block -third">
          stuff goes here.
        </div>
        <div className="container__block -third">
          stuff goes here.
        </div>
      </div>
    )
  }
}

export default Post;
