import React from 'react';
import { calculateAge, getImageUrlFromProp } from '../../helpers';
import { remove, map, clone } from 'lodash';

import Tags from '../Tags';
import InboxTile from './InboxTile';
import StatusButton from '../StatusButton';

function UserImages(props) {
  const userImage = getImageUrlFromProp(props.post);
  return (
    <div className="container__block -third">
      <img src={userImage}/>
      <p>
        <a href={userImage} target="_blank">Original Photo</a>
      </p>

      <ul className="gallery -duo">
        { map(props.getOtherPosts(props.post), (post, key) => <InboxTile key={key} details={post} />) }
      </ul>
    </div>
  );
}

function UserInfo(props) {
  return (
    <div>
      <h2>{props.post['user']['first_name']} {props.post['user']['last_name']}, {calculateAge(props.post['user']['birthdate'])}</h2>
      <ul>
      <li><em>{props.post['user']['email']}</em></li>
      <li><em>{props.post['user']['mobile']}</em></li>
      </ul>
    </div>
  );
}

function UserReportBack(props) {
  return (
    <div>
      <article className="figure -left -center">
        { props.signup.quantity ?
          <div>
            <div className="figure__media">
              <div className="quantity">{props.signup.quantity}</div>
            </div>
            <div className="figure__body">
              {props.campaign['reportback_info']['noun']} {props.campaign['reportback_info']['verb']}
            </div>
          </div>
          : null }
      </article>
      <a href="#" onClick={e => props.showHistory(props.post['id'], e)}>Edit | Show History</a>
      <br/>
      <br/>
      {props.post['caption'] ?
        <div>
          <h4>Photo Caption</h4>
          <p>{props.post['caption']}</p>
        </div>
        : null}
      <h4>Why Statement</h4>
      <p>{props.signup.why_participated}</p>
    </div>
  );
}

function UserInfoReport(props) {
  return (
    <div className="container__block -third">
      <UserInfo post={props.post} />
      <br/>
      <UserReportBack
        showHistory={props.showHistory}
        post={props.post}
        campaign={props.campaign}
        signup={props.signup} />
    </div>
  );
}

function PostMetaData(props){
  return (
    <div>
      <h4>Meta</h4>
      <p>
        Post ID: {props.post['id']} <br/>
        Post Status: {props.post['status']} <br/>
        Source: {props.post['source']} <br/>
        Submitted: {props.post['created_at']} <br/>
      </p>
    </div>
  );
}

function PostReview(props) {
  return (
    <div>
      {props.allowReview ?
        <div>
          <ul className="form-actions -inline">
            <li><StatusButton type="accepted" label="accept" status={props.post.status} setStatus={props.setStatus}/></li>
            <li><StatusButton type="rejected" label="reject" status={props.post.status} setStatus={props.setStatus}/></li>
          </ul>
          <ul className="form-actions -inline">
            <li><button className="button delete -tertiary" onClick={e => props.deletePost(props.post['id'], e)}>Delete</button></li>
          </ul>
          {props.post.status === 'accepted' ? <Tags id={props.post.id} tagged={props.post.tagged} onTag={props.onTag} /> : null}
        </div>
        : null }
    </div>
  )
}

function PostReviewAndMetaData(props) {
  return (
    <div className="container__block -third">
      <PostReview
        post={props.post}
        setStatus={props.setStatus}
        allowReview={props.allowReview}
        deletePost={props.deletePost}
        onTag={props.onTag} />
      <PostMetaData
        post={props.post} />
    </div>
  );
}

class InboxItem extends React.Component {
  constructor () {
    super();

    this.setStatus = this.setStatus.bind(this);
    this.getOtherPosts = this.getOtherPosts.bind(this);
  }

  // @todo - define this on the StatusButton component that can set some sort of global state.
  setStatus(status) {
    this.props.onUpdate(this.props.details.post.id, { status: status })
  }

  getOtherPosts(post) {
    const post_id = post['id'];
    const signup = this.props.details.signup;

    // get array of posts
    const other_posts = clone(signup.posts);

    // find index that has that post_id and remove
    const big_post = remove(other_posts, function(current_post) {
      return current_post['id'] == post_id;
    });

    // return the rest of the original array
    return other_posts;
  }

  render() {
    const post = this.props.details.post;
    const campaign = this.props.details.campaign;
    const signup = this.props.details.signup;
    return (
      <div className="container__row">
        <UserImages
          post={post}
          getOtherPosts={this.getOtherPosts} />
        <UserInfoReport
          post={post}
          campaign={campaign}
          signup={signup}
          showHistory={this.props.showHistory}/>
        <PostReviewAndMetaData
          post={post}
          setStatus={this.setStatus}
          allowReview={this.props.allowReview}
          deletePost={this.props.deletePost}
          onTag={this.props.onTag} />
      </div>
    )
  }
}

export default InboxItem;
