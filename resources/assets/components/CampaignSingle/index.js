import React from 'react';
import { flatMap, keyBy, map, sample, forEach, reject, filter } from 'lodash';

import InboxItem from '../InboxItem';
import ModalContainer from '../ModalContainer';
import HistoryModal from '../HistoryModal';
import PostFilter from '../PostFilter';
import StatusCounter from '../StatusCounter';
import { RestApiClient} from '@dosomething/gateway';

class CampaignSingle extends React.Component {
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
      filter: 'accepted',
      postTotals: props.post_totals,
      displayHistoryModal: false,
      historyModalId: null,
    };

    this.api = new RestApiClient;
    this.updateQuantity = this.updateQuantity.bind(this);
    this.showHistory = this.showHistory.bind(this);
    this.hideHistory = this.hideHistory.bind(this);
    this.filterPosts = this.filterPosts.bind(this);
  }

  filterPosts(status) {
    this.setState({ filter: status.toLowerCase() });
  }

  // Open the history modal of the given post
  // @TODO - Move this into the InboxItem component so
  // we don't have to duplicate this functionality when every we want to use Inbox Items.
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

  // Update a signups quanity.
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
            value.signup.quantity = result.quantity;
          }
        });

        // Return the new state
        return newState;
      });
    });

    // Close the modal
    this.hideHistory();
  }

  render() {
    const posts = this.state.posts;
    const campaign = this.props.campaign;

    return (
      <div className="container">
        <StatusCounter postTotals={this.state.postTotals} campaign={campaign} />
        <PostFilter onChange={this.filterPosts} />

        { map(posts, (post, key) => post.status === this.state.filter ? <InboxItem allowReview={false} onUpdate={this.updatePost} onTag={this.updateTag} showHistory={this.showHistory} deletePost={this.deletePost} key={key} details={{post: post, campaign: campaign}} /> : null) }

        <ModalContainer>
            {this.state.displayHistoryModal ? <HistoryModal id={this.state.historyModalId} onUpdate={this.updateQuantity} onClose={e => this.hideHistory(e)} details={{post: posts[this.state.historyModalId], campaign: campaign}}/> : null}
        </ModalContainer>
      </div>
    )
  }
}

export default CampaignSingle;
