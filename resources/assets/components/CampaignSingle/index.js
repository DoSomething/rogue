import React from 'react';
import { flatMap, keyBy, map, sample, forEach, reject, filter } from 'lodash';

import InboxItem from '../InboxItem';
import ModalContainer from '../ModalContainer';
import HistoryModal from '../HistoryModal';
import PostFilter from '../PostFilter';
import StatusCounter from '../StatusCounter';

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
      filteredPosts: posts,
      postTotals: props.post_totals,
      displayHistoryModal: false,
      historyModalId: null,
    };

    this.filterPosts = this.filterPosts.bind(this);
  }

  filterPosts(status) {
    const posts = filter(this.state.posts, {'status' : status.toLowerCase()});

    this.setState({ filteredPosts: posts });
  }

  render() {
    const posts = this.state.filteredPosts;
    const campaign = this.props.campaign;

    return (
      <div className="container">
        <StatusCounter postTotals={this.state.postTotals} campaign={campaign} />
        <PostFilter onChange={this.filterPosts} />

        { map(posts, (post, key) => <InboxItem allowReview={false} onUpdate={this.updatePost} onTag={this.updateTag} showHistory={this.showHistory} deletePost={this.deletePost} key={key} details={{post: post, campaign: campaign}} />) }
      </div>
    )
  }
}

export default CampaignSingle;
