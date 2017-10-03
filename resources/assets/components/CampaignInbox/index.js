import React from 'react';
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
      return <div className="spinner"></div>;
    }

    const posts = this.props.posts;
    const campaign = this.props.campaign;
    const signups = this.props.signups;

    if (!isEmpty(posts)) {
      return (
        <div className="container">
          {
            map(this.props.postIds, (key, value) => {
              var post = find(posts, {'id': key});

              return <Post key={key}
                post={post}
                user={signups[post.signup_id].user.data}
                signup={signups[post.signup_id]}
                campaign={campaign}
                onUpdate={this.props.updatePost}
                onTag={this.props.updateTag}
                deletePost={this.props.deletePost}
                showHistory={this.props.showHistory}
                rotate={this.props.rotate}
                showSiblings={true}
                showQuantity={true}
                allowHistory={true} />
            })
          }

          <ModalContainer>
            {this.props.displayHistoryModal ?
              <HistoryModal
                id={this.props.historyModalId}
                onUpdate={this.props.updateQuantity}
                onClose={e => this.props.hideHistory(e)}
                campaign={campaign}
                signup={signups[posts[this.props.historyModalId]['signup_id']]}
              />
            : null}
          </ModalContainer>
        </div>
      )
    } else {
      return <Empty header="There are no new posts!" copy="Great job, there are no new posts to review! You can check out all posts for this campaign here" />
    }
  }
}

export default CampaignInbox;
