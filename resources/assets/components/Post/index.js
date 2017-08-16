import React from 'react';
import { remove, map, clone } from 'lodash';
import { getImageUrlFromProp, getEditedImageUrl, displayCaption } from '../../helpers';

import Tags from '../Tags';
import TextBlock from '../TextBlock';
import ReviewBlock from '../ReviewBlock';
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
          <div className="container -padded">
            <TextBlock title="Photo Caption" content={displayCaption(post)} />
          </div>
          <div className="container">
            <TextBlock title="Why Statement" content={this.props.signup.why_participated} />
          </div>
        </div>
        <div className="container__block -third">
          <ReviewBlock post={post} />
        </div>
      </div>
    )
  }
}

export default Post;
