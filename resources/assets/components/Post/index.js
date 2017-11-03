import React from 'react';
import PropTypes from 'prop-types';

import { remove, map, clone } from 'lodash';
import { RestApiClient } from '@dosomething/gateway';
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

    this.api = new RestApiClient;
    this.handleClick = this.handleClick.bind(this);
  }

  handleClick(event) {
    event.preventDefault();

    this.setState({ loading: true });

    this.props.rotate(this.props.post.id)
      .then(() => {
        this.setState({ 'loading' : false });
      });
  }

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
      <div className="post container__row">
        {/* Post Images */}
        <div className="container__block -third images">
          <div className="post__image">
            <img src={getImageUrlFromPost(post, 'edited')}/>
          </div>
          <div className="admin-tools">
            <div className="admin-tools__links">
              <a href={getImageUrlFromPost(post, 'original')} target="_blank">Original Photo</a>
              <br />
              <a href={getImageUrlFromPost(post, 'edited')} target="_blank">Edited Photo</a>
            </div>
            {this.props.rotate ?
              <div className="admin-tools__rotate">
                {this.state.loading ?
                  <div className="spinner"></div>
                :
                  <a className="button -tertiary rotate" onClick={this.handleClick}></a>
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

        {/* User and Post information */}
        <div className="container__block -third">
          <UserInformation user={user} linkSignup={signup.signup_id}>
            {signup.quantity && this.props.showQuantity ?
              <Quantity quantity={signup.quantity} noun={campaign.reportback_info.noun} verb={campaign.reportback_info.verb} />
            : null}

            {this.props.allowHistory ?
              <div className="container">
                <a href="#" onClick={e => this.props.showHistory(post['id'], e, signup.signup_id)}>Edit | Show History</a>
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
              "Source": post.source,
              "Submitted": new Date(post.created_at).toDateString(),
              "Northstar Id": signup.northstar_id
            }} />
          </div>
        </div>
      </div>
    )
  }
}

Post.propTypes = {
  allowHistory: PropTypes.bool,
  campaign: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
  deletePost: PropTypes.func.isRequired,
  onTag: PropTypes.func.isRequired,
  onUpdate: PropTypes.func.isRequired,
  post: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
  rotate: PropTypes.func.isRequired,
  showHistory: PropTypes.func,
  showQuantity: PropTypes.bool,
  showSiblings: PropTypes.bool.isRequired,
  signup: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
  user: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
};

Post.defaultProps = {
  allowHistory: false,
  showHistory: null,
  showQuantity: false,
};

export default Post;
