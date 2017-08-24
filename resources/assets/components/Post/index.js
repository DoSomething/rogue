import React from 'react';
import { remove, map, clone } from 'lodash';
import { getImageUrlFromProp, getEditedImageUrl, displayCaption } from '../../helpers';

import Tags from '../Tags';
import TextBlock from '../TextBlock';
import ReviewBlock from '../ReviewBlock';
import StatusButton from '../StatusButton';
import MetaInformation from '../MetaInformation';

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
          <div className="container__row">
            <ReviewBlock post={post} onUpdate={this.props.onUpdate} onTag={this.props.onTag} deletePost={this.props.deletePost} />
          </div>
          <div className="container__row">
            <MetaInformation title="Meta" details={{
              "Post ID": post.id,
              "Submitted": new Date(post.created_at).toDateString(),
              "Source": post.source,
            }} />
          </div>
        </div>
      </div>
    )
  }
}

export default Post;
