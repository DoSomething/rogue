import React from 'react';
import { flatMap, keyBy, map, sample, forEach, reject, filter } from 'lodash';
import { extractPostsFromSignups } from '../../helpers';

import InboxItem from '../InboxItem';
import ModalContainer from '../ModalContainer';
import HistoryModal from '../HistoryModal';
import PagingButtons from '../PagingButtons';
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
      nextPage: props.next_page,
      prevPage: props.previous_page,
    };

    this.api = new RestApiClient;
    this.updateQuantity = this.updateQuantity.bind(this);
    this.showHistory = this.showHistory.bind(this);
    this.hideHistory = this.hideHistory.bind(this);
    this.filterPosts = this.filterPosts.bind(this);
  }

  // Filter posts based on status.
  filterPosts(status) {
    // @TODO: Make API request to Rogue to get filtered posts and and set the post's filter state based on response.
    // @TODO: we also need to update the posts/signups?
    let request = this.api.get('api/v1/reportbacks', {
      filter: {
        status: status.toLowerCase()
      }
    });

    request.then((result) => {
      console.log(result);
      // Update the state
      this.setState((previousState) => {
        const newState = {...previousState};

      //   // newState.signups[signup.id].quantity = result.quantity;

      //   return newState;
      });
    });

    // this.setState({ filter: status.toLowerCase() });
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
        <StatusCounter postTotals={this.state.postTotals} campaign={campaign} />

        <PostFilter onChange={this.filterPosts} />

        { map(posts, (post, key) => post.status === this.state.filter ? <InboxItem allowReview={false} onUpdate={this.updatePost} onTag={this.updateTag} showHistory={this.showHistory} deletePost={this.deletePost} key={key} details={{post: post, campaign: campaign, signup: this.state.signups[post.signup_id]}} /> : null) }

        <ModalContainer>
            {this.state.displayHistoryModal ? <HistoryModal id={this.state.historyModalId} onUpdate={this.updateQuantity} onClose={e => this.hideHistory(e)} details={{post: posts[this.state.historyModalId], campaign: campaign, signups: this.state.signups}}/> : null}
        </ModalContainer>

        <PagingButtons prev={this.state.prevPage} next={this.state.nextPage}></PagingButtons>
      </div>
    )
  }
}

export default CampaignSingle;
