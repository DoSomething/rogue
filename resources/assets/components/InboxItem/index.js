import React from 'react';
import { calculateAge, getImageUrlFromProp } from '../../helpers';
import { remove, map, clone } from 'lodash';

import Tags from '../Tags';
import InboxTile from './InboxTile';
import StatusButton from '../StatusButton';

class InboxItem extends React.Component {
  constructor () {
    super();

    this.setStatus = this.setStatus.bind(this);
    this.updateTag = this.updateTag.bind(this);
  }

  // @todo - define this on the StatusButton component that can set some sort of global state.
  setStatus(status) {
    this.props.onUpdate(this.props.details.post.id, { status: status })
  }

  updateTag(tag) {
    this.props.onUpdate(this.props)
  }

  getOtherPosts(post) {
    const post_id = post['id'];

    // get array of posts
    const other_posts = clone(post['signup']['posts']);

    // find index that has that post_id and remove
    const big_post = remove(other_posts, function(current_post) {
      return current_post['id'] == post_id;
    });

    // return the rest of the original array
    return other_posts;
  }

  render() {
    const post = this.props.details['post'];
    const campaign = this.props.details['campaign'];

    return (
      <div className="container__row">
        <div className="container__block -third">
          <img src={getImageUrlFromProp(post)}/>
          <p>
            <a href={getImageUrlFromProp(post)} target="_blank">Original Photo</a>
          </p>

          <ul className="gallery -quartet">
          { map(this.getOtherPosts(post), (post, key) => <InboxTile key={key} details={post} />) }
          </ul>
        </div>
        <div className="container__block -third">
          <h2>{post['user']['first_name']} {post['user']['last_name']}, {calculateAge(post['user']['birthdate'])}</h2>
          <ul>
            <li><em>{post['user']['email']}</em></li>
            <li><em>{post['user']['mobile']}</em></li>
          </ul>
          <br/>
          <article className="figure -left -center">
            <div className="figure__media">
              <div className="quantity">{post['signup']['quantity']}</div>
            </div>
            <div className="figure__body">
              {campaign['reportback_info']['noun']} {campaign['reportback_info']['verb']}
            </div>
          </article>
          <a href="#" onClick={e => this.props.showHistory(post['id'], e)}>Edit | Show History</a>
          <br/>
          <h4>Photo Caption</h4>
          <p>{post['caption']}</p>
          <h4>Why Statement</h4>
          <p>{post['signup']['why_participated']}</p>
        </div>
        <div className="container__block -third">
          <ul className="form-actions -inline">
            <li><StatusButton type="accepted" setStatus={this.setStatus}/></li>
            <li><StatusButton type="rejected" setStatus={this.setStatus}/></li>
          </ul>
          <ul className="form-actions -inline">
            <li><button className="button -tertiary" onClick={e => this.props.deletePost(post['id'], e)}>Delete</button></li>
          </ul>
          {post.status === 'accepted' ? <Tags id={post.id} tagged={post.tagged} onTag={this.props.onTag} /> : null}
          <h4>Meta</h4>
          <p>
            Post ID: {post['id']} <br/>
            Source: {post['source']} <br/>
            Submitted: {post['created_at']} <br/>
          </p>
        </div>
      </div>
    )
  }
}

export default InboxItem;
