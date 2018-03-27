import React from 'react';
import PropTypes from 'prop-types';

import { remove, map, clone } from 'lodash';
import { getImageUrlFromPost, getEditedImageUrl, displayCaption } from '../../helpers';

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
  constructor(props) {
    super(props);

    this.state = {
      loading: false,
    };

    this.handleClick = this.handleClick.bind(this);
  }

  handleClick(event) {
    event.preventDefault();

    this.setState({ loading: true });

    this.props.rotate(this.props.post.id)
      .then(() => {
        this.setState({ loading: false });
      });
  }

  getOtherPosts(post) {
    const post_id = post.id;
    const signup = this.props.signup;

    const posts = signup.posts ? signup.posts : post.siblings.data;

    // get array of posts
    const other_posts = clone(posts);

    // find index that has that post_id and remove
    const big_post = remove(other_posts, current_post => current_post.id == post_id);

    // return the rest of the original array
    return other_posts;
  }

  render() {
    const post = this.props.post;
    const caption = displayCaption(post);
    const user = this.props.user ? this.props.user : null;
    const signup = this.props.signup;
    const campaign = this.props.campaign;
    const quantity = post.quantity != null ? post.quantity : 0;
    const containerSize = post.type === 'photo' ? '-third' : '-half';

    return (
      <div className="post container__row">
        {/* Post Images */}
        {post.type === 'photo' ?
          <div className="container__block -third">
            <div className="post__image">
              <img src={getImageUrlFromPost(post, 'original')} />
            </div>
            <div className="admin-tools">
              <div className="admin-tools__links">
                <a href={getImageUrlFromPost(post, 'original')} target="_blank">Original Photo</a>
              </div>
              {this.props.rotate ?
                <div className="admin-tools__rotate">
                  {this.state.loading ?
                    <div className="spinner" />
                    :
                    <a className="button -tertiary rotate" onClick={this.handleClick} />
                  }
                </div>
                : null}
            </div>
            {this.props.showSiblings ?
              <ul className="gallery -duo">
                {
                  map(this.getOtherPosts(post), (post, key) => <PostTile key={key} details={post} />)
                }
              </ul>
              : null}
          </div>
          : null}

        {/* User and Post information */}
        <div className={"container__block " + containerSize}>
          <UserInformation user={user} linkSignup={signup.signup_id}>
            {this.props.showQuantity ?
              <Quantity quantity={quantity} noun={campaign.reportback_info.noun} verb={campaign.reportback_info.verb} />
              : null}

            {this.props.allowHistory ?
              <div className="container">
                <a href="#" onClick={e => this.props.showHistory(post.id, e, signup.signup_id)}>Edit | Show History</a>
              </div>
              : null}

            <div className="container -padded">
              <TextBlock title="Text" content={displayCaption(post)} />
            </div>

            <div className="container">
              <TextBlock title="Why Statement" content={this.props.signup.why_participated} />
            </div>
          </UserInformation>
        </div>

        {/* Review block and meta data */}
        <div className={"container__block " + containerSize}>
          <div className="container__row">
            <ReviewBlock post={post} onUpdate={this.props.onUpdate} onTag={this.props.onTag} deletePost={this.props.deletePost} />
          </div>
          <div className="container__row">
            <MetaInformation
              title="Meta"
              details={{
                'Post ID': post.id,
                Source: post.source,
                Submitted: new Date(post.created_at).toDateString(),
                'Northstar Id': signup.northstar_id,
              }}
            />
          </div>
        </div>
      </div>
    );
  }
}

Post.propTypes = {
  allowHistory: PropTypes.bool,
  campaign: PropTypes.object, // eslint-disable-line react/forbid-prop-types
  deletePost: PropTypes.func.isRequired,
  onTag: PropTypes.func.isRequired,
  onUpdate: PropTypes.func.isRequired,
  post: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
  rotate: PropTypes.func,
  showHistory: PropTypes.func,
  showQuantity: PropTypes.bool,
  showSiblings: PropTypes.bool,
  signup: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
  // @TODO: Sometimes comes in as an array, and sometimes as an object.
  // Figure out why, and update the validation.
  user: PropTypes.oneOfType([
    PropTypes.array,
    PropTypes.object,
  ]), // eslint-disable-line react/forbid-prop-types
};

Post.defaultProps = {
  allowHistory: false,
  campaign: null,
  rotate: null,
  showHistory: null,
  showQuantity: false,
  showSiblings: false,
  user: null,
};

export default Post;
