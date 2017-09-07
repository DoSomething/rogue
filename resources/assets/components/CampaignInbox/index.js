import React from 'react';
import { map, sample } from 'lodash';
import { RestApiClient } from '@dosomething/gateway';

import { extractPostsFromSignups } from '../../helpers';
import Post from '../Post';
import HistoryModal from '../HistoryModal';
import ModalContainer from '../ModalContainer';

class CampaignInbox extends React.Component {
  render() {
    const posts = this.props.posts;
    const users = this.props.users;
    const campaign = this.props.campaign;
    const signups = this.props.signups;

    const nothingHere = [
      'https://media.giphy.com/media/3og0IT9dAZyMz3lXNe/giphy.gif',
      'https://media.giphy.com/media/Lny6Rw04nsOOc/giphy.gif',
      'https://media.giphy.com/media/YdhvjTeL83pNS/giphy.gif',
      'https://media.giphy.com/media/26ufnwz3wDUli7GU0/giphy.gif',
      'https://media.giphy.com/media/lYHbL5QY52Kcw/giphy.gif',
    ];

    if (posts.length !== 0) {
      return (
        <div className="container">
          {
            map(posts, (post, key) =>
              <Post key={key}
                post={post}
                user={users[post.northstar_id]}
                signup={signups[post.signup_id]}
                campaign={campaign}
                onUpdate={this.props.updatePost}
                onTag={this.props.updateTag}
                deletePost={this.props.deletePost}
                showHistory={this.props.showHistory}
                showSiblings={true}
                showQuantity={true}
                allowHistory={true} />
            )
          }

          <ModalContainer>
            {this.props.displayHistoryModal ?
              <HistoryModal
                id={this.props.historyModalId}
                onUpdate={this.props.updateQuantity}
                onClose={e => this.hideHistory(e)}
                campaign={campaign}
                signup={signups[posts[this.props.historyModalId]['signup_id']]}
              />
            : null}
          </ModalContainer>
        </div>
      )
    } else {
      // @todo - make this into an actual component.
      return (
        <div className="container">
          <h2 className="-padded">No Posts to review!</h2>
          <div className="container">
            <img src={sample(nothingHere)} />
          </div>
        </div>
      )
    }
  }
}

export default CampaignInbox;
