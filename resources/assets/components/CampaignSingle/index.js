import React from 'react';
import { flatMap, keyBy, map, sample, forEach, reject, filter } from 'lodash';
import { extractPostsFromSignups } from '../../helpers';

import InboxItem from '../InboxItem';
import ModalContainer from '../ModalContainer';
import HistoryModal from '../HistoryModal';
import PostFilter from '../PostFilter';
import StatusCounter from '../StatusCounter';
import { RestApiClient} from '@dosomething/gateway';

class CampaignSingle extends React.Component {
  constructor(props) {
    super(props);

    const posts = extractPostsFromSignups(props.signups);

    this.state = {
      signups: keyBy(props.signups, 'id'),
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

  // Filter posts based on status.
  filterPosts(status) {
    this.setState({ filter: status.toLowerCase() });
  }

  // Open the history modal of the given post
  // @TODO - Figure out how to share this logic between components so it
  // doesn't need to be duplicated between components.
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

  render() {
    const posts = this.state.posts;
    const campaign = this.props.campaign;

    return (
      <div className="container">
        <div className="container__block">
          <p>
            Hey there 🐼! Why’s there only ~100 photos on this page you ask? <br/>
            Good question! We’re only loading ~100 photos to help this page load <em>faster</em> for you! We’re working on showing all the photos while keeping the page load time <em>fast</em>  - this should be done the by 8/1! Stay tuned!<br/>
            If you  need to see more accepted posts, reach out in #the-bleed in Slack!<br/>
            Love,<br/>
            Team Bleed
          </p>
        </div>
        <StatusCounter postTotals={this.state.postTotals} campaign={campaign} />
        <PostFilter onChange={this.filterPosts} />

        { map(posts, (post, key) => post.status === this.state.filter ? <InboxItem allowReview={false} onUpdate={this.updatePost} onTag={this.updateTag} showHistory={this.showHistory} deletePost={this.deletePost} key={key} details={{post: post, campaign: campaign, signup: this.state.signups[post.signup_id]}} /> : null) }

        <ModalContainer>
            {this.state.displayHistoryModal ? <HistoryModal id={this.state.historyModalId} onUpdate={this.updateQuantity} onClose={e => this.hideHistory(e)} details={{post: posts[this.state.historyModalId], campaign: campaign, signups: this.state.signups}}/> : null}
        </ModalContainer>
      </div>
    )
  }
}

export default CampaignSingle;
