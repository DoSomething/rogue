import React from 'react';
import PropTypes from 'prop-types';
import { map, find, isEmpty } from 'lodash';
import { RestApiClient } from '@dosomething/gateway';

import { extractPostsFromSignups } from '../../helpers';
import Post from '../Post';
import Empty from '../Empty';
import HistoryModal from '../HistoryModal';
import ModalContainer from '../ModalContainer';

class CampaignInbox extends React.Component {
  render() {
    if (this.props.loading) {
      return <div className="spinner" />;
    }

    const posts = this.props.posts;
    const campaign = this.props.campaign;
    const signups = this.props.signups;

    if (! isEmpty(posts)) {
      return (
        <div className="container">
          {
            map(this.props.postIds, (key, value) => {
              const post = find(posts, { id: key });

              return (<Post
                key={key}
                post={post}
                user={signups[post.signup_id].user.data}
                signup={signups[post.signup_id]}
                campaign={campaign}
                onUpdate={this.props.updatePost}
                onTag={this.props.updateTag}
                deletePost={this.props.deletePost}
                showHistory={this.props.showHistory}
                rotate={this.props.rotate}
                showSiblings
                showQuantity
                allowHistory
              />);
            })
          }

          <ModalContainer>
            {this.props.displayHistoryModal ?
              <HistoryModal
                id={this.props.historyModalId}
                onUpdate={this.props.updateQuantity}
                onClose={e => this.props.hideHistory(e)}
                campaign={campaign}
                signup={signups[posts[this.props.historyModalId].signup_id]}
                signupEvents={this.props.signupEvents}
                post={posts[this.props.historyModalId]}
              />
              : null}
          </ModalContainer>
        </div>
      );
    }
    return <Empty header="There are no new posts!" copy="Great job, there are no new posts to review! You can check out all posts for this campaign here" />;
  }
}

CampaignInbox.propTypes = {
  campaign: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
  deletePost: PropTypes.func.isRequired,
  displayHistoryModal: PropTypes.bool,
  hideHistory: PropTypes.func.isRequired,
  historyModalId: PropTypes.number,
  loading: PropTypes.bool.isRequired,
  postIds: PropTypes.arrayOf(PropTypes.number),
  posts: PropTypes.object, // eslint-disable-line react/forbid-prop-types
  rotate: PropTypes.func.isRequired,
  showHistory: PropTypes.func.isRequired,
  signups: PropTypes.object, // eslint-disable-line react/forbid-prop-types
  signupEvents: PropTypes.array, // eslint-disable-line react/forbid-prop-types
  updatePost: PropTypes.func.isRequired,
  updateQuantity: PropTypes.func.isRequired,
  updateTag: PropTypes.func.isRequired,
};

CampaignInbox.defaultProps = {
  displayHistoryModal: null,
  historyModalId: null,
  postIds: null,
  posts: null,
  signups: null,
  signupEvents: null,
};

export default CampaignInbox;
