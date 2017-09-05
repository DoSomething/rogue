import React from 'react';
import { remove, map, clone } from 'lodash';
import { getImageUrlFromProp, getEditedImageUrl, displayCaption } from '../../helpers';

import './post.scss';

import Tags from '../Tags';
import PostTile from '../PostTile';
import Quantity from '../Quantity';
import TextBlock from '../TextBlock';
import ReviewBlock from '../ReviewBlock';
import StatusButton from '../StatusButton';
import MetaInformation from '../MetaInformation';
import UserInformation from '../Users/UserInformation';

class Post extends React.Component {
  getOtherPosts(post) {
    const post_id = post['id'];
    const signup = this.props.signup;

    var posts = signup.posts ? signup.posts : post.siblings.data;

    // get array of posts
    const other_posts = clone(posts);

    // find index that has that post_id and remove
    const big_post = remove(other_posts, function(current_post) {
      return current_post['id'] == post_id;
    });

    // return the rest of the original array
    return other_posts;
  }

  render() {
    const post = this.props.post;
    const caption = displayCaption(post);
    const user = this.props.user ? this.props.user : null;
    const signup = this.props.signup;
    const campaign = this.props.campaign;

    return (
      <div className="container__row">
        {/* Post Images */}
        <div className="container__block -third">
          <img src={getImageUrlFromProp(post)}/>
          <p>
            <a href={getImageUrlFromProp(post)} target="_blank">Original Photo</a> | <a href={getEditedImageUrl(post)} target="_blank">Edited Photo</a>
          </p>
          {this.props.showSiblings ?
            <ul className="gallery -duo">
              {
                map(this.getOtherPosts(post), (post, key) => <PostTile key={key} details={post} />)
              }
            </ul>
          : null}
        </div>

        {/* User and Post information */}
        <div className="container__block -third">
          <UserInformation user={user}>
            {signup.quantity && this.props.showQuantity ?
              <Quantity quantity={signup.quantity} noun={campaign.reportback_info.noun} verb={campaign.reportback_info.verb} />
            : null}

            {this.props.allowHistory ?
              <div className="container">
                <a href="#" onClick={e => this.props.showHistory(post['id'], e)}>Edit | Show History</a>
              </div>
            : null}

            <div className="container -padded">
              <TextBlock title="Photo Caption" content={displayCaption(post)} />
            </div>

            <div className="container">
              <TextBlock title="Why Statement" content={this.props.signup.why_participated} />
            </div>
          </UserInformation>
        </div>

        {/* Review block and meta data */}
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
