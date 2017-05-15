import React from 'react';
import { flatMap, keyBy, map, sample, forEach } from 'lodash';
import { RestApiClient} from '@dosomething/gateway';

import InboxItem from '../InboxItem';
import ModalContainer from '../ModalContainer';
import HistoryModal from '../HistoryModal';

class CampaignInbox extends React.Component {
  constructor(props) {
    super(props);

    const posts = keyBy(flatMap(props.signups, signup => {
      return signup.posts.map(post => {
        post.signup = signup;
        return post;
      });
    }), 'id');

    this.state = {
      posts: posts,
      displayHistoryModal: false,
      historyModalId: null,
    };

    this.api = new RestApiClient;
    this.updatePost = this.updatePost.bind(this);
    this.updateQuantity = this.updateQuantity.bind(this);
    this.showHistory = this.showHistory.bind(this);
    this.hideHistory = this.hideHistory.bind(this);
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
    event.preventDefault()

    this.setState({
      displayHistoryModal: false,
      historyModalId: null,
    });
  }

  updatePost(postId, fields) {
    // @TODO: Make API request to Rogue.
    // right now, fields only returns status. We need to add post_id (which we have in postId)
    // and admin_northstar_id.
    let response = this.api.put('api/v2/reviews', fields);

    this.setState((previousState) => {
      const newState = {...previousState};
      newState.posts[postId].status = fields.status;

      // @TODO: Update this based on the response from API!
      newState.posts[postId] = response.data;

      return newState;
    });
  }

  updateQuantity(post, newQuantity) {
    // Fields to send to /posts
    const fields = {
      northstar_id: post.user.id,
      campaign_id: post.signup.campaign_id,
      campaign_run_id: post.signup.campaign_run_id,
      quantity: newQuantity,
    };

    // Make API request to Rogue to update the quantity on the backend
    let response = this.api.post('api/v2/posts', fields);

    response.then((result) => {
      // Update the state
      this.setState((previousState) => {
        const newState = {...previousState};
        const signupChanged = post.signup_id;

        // Update the quantity for each post under this signup
        forEach (newState.posts, (value) => {
          if (value.signup_id == signupChanged) {
            value.signup.quantity = newQuantity;
          }
        });

        // Return the new state
        return newState;
      });
    });

    // Close the modal
    this.hideHistory(event);
  }

  render() {
    const posts = this.state.posts;
    const campaign = this.props.campaign;

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
          { map(posts, (post, key) => <InboxItem onUpdate={this.updatePost} showHistory={this.showHistory} key={key} details={{post: post, campaign: campaign}} />) }
          <ModalContainer>
            {this.state.displayHistoryModal ? <HistoryModal id={this.state.historyModalId} onUpdate={this.updateQuantity} onClose={e => this.hideHistory(e)} details={{post: posts[this.state.historyModalId], campaign: campaign}}/> : null}
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
