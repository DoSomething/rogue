import React from 'react';
import { keyBy, map, sample, forEach, reject } from 'lodash';
import { RestApiClient } from '@dosomething/gateway';

import { extractPostsFromSignups } from '../../helpers';
import InboxItem from '../InboxItem';
import ModalContainer from '../ModalContainer';
import HistoryModal from '../HistoryModal';
import Confetti from 'react-dom-confetti';
import classnames from 'classnames';


class CampaignInbox extends React.Component {
  constructor(props) {
    super(props);

    const posts = extractPostsFromSignups(props.signups);
    const allPosts = Object.keys(posts).map(elem => posts[elem]);
    const initialPost = allPosts.slice(0, 5).reduce((posts, postElem) => {
      posts[postElem.id] = postElem;
      return posts;
    }, {});

    this.state = {
      allPosts: allPosts,
      currentBatch: 1,
      celebrateClick: false,
      displayHistoryModal: false,
      historyModalId: null,
      nextBatchButtonEnabled: false,
      numberOfPosts: 5,
      posts: initialPost,
      signups: keyBy(props.signups, 'id')
    };

    this.api             = new RestApiClient;
    this.updatePost      = this.updatePost.bind(this);
    this.updateTag       = this.updateTag.bind(this);
    this.updateQuantity  = this.updateQuantity.bind(this);
    this.showHistory     = this.showHistory.bind(this);
    this.hideHistory     = this.hideHistory.bind(this);
    this.deletePost      = this.deletePost.bind(this);
    this.getPostBatch    = this.getPostBatch.bind(this);
    this.batchIsComplete = this.batchIsComplete.bind(this);
  }

  batchIsComplete(posts) {
    return Object.keys(posts)
      .map(postKey => posts[postKey])
      .every(post => post.status !== 'pending');
  }

  getPostBatch() {
    const from          = this.state.currentBatch * this.state.numberOfPosts;
    const to            = from + this.state.numberOfPosts;
    const nextBatch     = this.state.currentBatch + 1;
    const nextPostBatch = this.state.allPosts.slice(from, to).reduce((posts, postElem) => {
      posts[postElem.id] = postElem;
      return posts;
    }, {});


    this.setState({
      currentBatch: nextBatch,
      posts: nextPostBatch,
      nextBatchButtonEnabled: false,
      celebrateClick: true
    });

  }

  // Updates a post status.
  updatePost(postId, fields) {
    const isEndOfPosts = this.state.currentBatch * this.state.numberOfPosts >= this.state.allPosts.length;
    fields.post_id     = postId;
    const request      = this.api.put('reviews', fields);

    request.then(() => {
      this.setState((previousState) => {
        const newState                  = {...previousState};
        newState.posts[postId].status   = fields.status;
        newState.nextBatchButtonEnabled = this.batchIsComplete(newState.posts);

        if (isEndOfPosts && this.batchIsComplete(newState.posts)) {
          newState.celebrateClick = true;
        } else {
          newState.celebrateClick = false;
        }

        return newState;
      });
    });

  }

  // Open the history modal of the given post
  showHistory(postId, event) {
    event.preventDefault()

    this.setState({
      displayHistoryModal: true,
      historyModalId: postId,
    });
  }

  // Close the open history modal
  hideHistory(event) {
    if (event) {
      event.preventDefault();
    }

    this.setState({
      displayHistoryModal: false,
      historyModalId: null,
    });
  }

  // Tag a post.
  updateTag(postId, tag) {
    const fields = {
      post_id: postId,
      tag_name: tag,
    };

    let response = this.api.post('tags', fields);
    response.then((data) => {
      this.setState((previousState) => {
        const newState = {...previousState};
        const user = newState.posts[postId].user;

        newState.posts[postId] = data;

        // Keep the user from the initial page load.
        newState.posts[postId].user = user;

        return newState;
      });
    });
  }

  // Update a signups quanity.
  updateQuantity(signup, newQuantity) {
    // Fields to send to /posts
    const fields = {
      northstar_id: signup.northstar_id,
      campaign_id: signup.campaign_id,
      campaign_run_id: signup.campaign_run_id,
      quantity: newQuantity,
    };

    // Make API request to Rogue to update the quantity on the backend
    let request = this.api.post('posts', fields);

    request.then((result) => {
      // Update the state
      this.setState((previousState) => {
        const newState = {...previousState};

        newState.signups[signup.id].quantity = result.quantity;

        return newState;
      });
    });

    // Close the modal
    this.hideHistory();
  }

  // Delete a post.
  deletePost(postId, event) {
    event.preventDefault();
    const confirmed = confirm('🚨🔥🚨Are you sure you want to delete this?🚨🔥🚨');

    if (confirmed) {
      // Make API request to Rogue to update the quantity on the backend
      let response = this.api.delete('posts/'.concat(postId));

      response.then((result) => {
        // Update the state
        this.setState((previousState) => {
          var newState = {...previousState};

          // Remove the deleted post from the state
          delete(newState.posts[postId]);

          // Return the new state
          return newState;
        });
      });
    }
  }

  render() {
    const posts        = this.state.posts;
    const campaign     = this.props.campaign;
    const endOfPosts   = this.state.currentBatch * this.state.numberOfPosts >= this.state.allPosts.length;
    const modalDetails = {
      post: posts[this.state.historyModalId],
      campaign: campaign,
      signups: this.state.signups
    };
    const config = {
      angle: 90,
      spread: 60,
      startVelocity: 28,
      elementCount: 40,
      decay: 0.95
    };

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

          { map(posts, (post, key) =>
            <InboxItem
              allowReview={true}
              onUpdate={this.updatePost}
              onTag={this.updateTag}
              showHistory={this.showHistory}
              deletePost={this.deletePost}
              key={key}
              details={{
                post: post,
                campaign: campaign,
                signup: this.state.signups[post.signup_id]
              }} />) }

          <ModalContainer>
            {this.state.displayHistoryModal ?
              <HistoryModal
                id={this.state.historyModalId}
                onUpdate={this.updateQuantity}
                onClose={e => this.hideHistory(e)}
                details={modalDetails}/>
              : null}
          </ModalContainer>
            <button
              className={classnames('button', 'accepted')}
              disabled={!this.state.nextBatchButtonEnabled}
              onClick={this.getPostBatch}>
              <Confetti active={this.state.celebrateClick} config={config} />
              {endOfPosts ? "DONE!" : "GIVE ME MORE!"}
            </button>
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
