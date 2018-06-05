import React from 'react';
import PropTypes from 'prop-types';

import Tags from '../Tags';
import StatusButton from '../StatusButton';

class ReviewBlock extends React.Component {
  setStatus(status) {
    return this.props.onUpdate(this.props.post, { status });
  }

  render() {
    const post = this.props.post;
    const disableTags = !(
      post.status === 'accepted' || post.status === 'rejected'
    );

    return (
      <div>
        <ul className="form-actions -inline" style={{ marginTop: 0 }}>
          <li>
            <StatusButton
              type="accepted"
              label="accept"
              status={post.status}
              setStatus={this.setStatus.bind(this)}
            />
          </li>
          <li>
            <StatusButton
              type="rejected"
              label="reject"
              status={post.status}
              setStatus={this.setStatus.bind(this)}
            />
          </li>
        </ul>
        <ul className="form-actions -inline">
          <li>
            <button
              className="button delete -tertiary"
              onClick={e => this.props.deletePost(post.id, e)}
            >
              Delete
            </button>
          </li>
        </ul>
        <Tags
          id={post.id}
          tagged={post.tags}
          onTag={this.props.onTag}
          disabled={disableTags}
        />
      </div>
    );
  }
}

ReviewBlock.propTypes = {
  deletePost: PropTypes.func.isRequired,
  onUpdate: PropTypes.func.isRequired,
  onTag: PropTypes.func.isRequired,
  post: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
};

export default ReviewBlock;
