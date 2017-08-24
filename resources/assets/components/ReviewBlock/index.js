import React from 'react';

import Tags from '../Tags';
import StatusButton from '../StatusButton';

class ReviewBlock extends React.Component {
  setStatus(status) {
    this.props.onUpdate(this.props.post.id, { status: status });
  }

  render() {
    const post = this.props.post;

    return (
      <div>
        <ul className="form-actions -inline" style={{'marginTop': 0}}>
          <li><StatusButton type="accepted" label="accept" status={post.status} setStatus={this.setStatus.bind(this)}/></li>
          <li><StatusButton type="rejected" label="reject" status={post.status} setStatus={this.setStatus.bind(this)}/></li>
        </ul>
        <ul className="form-actions -inline">
          <li><button className="button delete -tertiary" onClick={e => this.props.deletePost(post['id'], e)}>Delete</button></li>
        </ul>
        {post.status === 'accepted' ?
          <Tags id={post.id} tagged={post.tagged} onTag={this.props.onTag} />
        : null}
      </div>
    )
  }
}

export default ReviewBlock;
