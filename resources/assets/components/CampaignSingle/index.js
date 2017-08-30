import React from 'react';
import { flatMap, keyBy, map, sample, forEach, reject, filter } from 'lodash';
import { RestApiClient} from '@dosomething/gateway';

import InboxItem from '../InboxItem';
import ModalContainer from '../ModalContainer';
import HistoryModal from '../HistoryModal';
import PagingButtons from '../PagingButtons';
import PostFilter from '../PostFilter';
import StatusCounter from '../StatusCounter';

class CampaignSingle extends React.Component {
  constructor(props) {
    super(props);

    this.state = {};

    this.api = new RestApiClient;
    this.updatePost = this.updatePost.bind(this);
    this.updateTag = this.updateTag.bind(this);
    this.updateQuantity = this.updateQuantity.bind(this);
    this.showHistory = this.showHistory.bind(this);
    this.hideHistory = this.hideHistory.bind(this);
    this.deletePost = this.deletePost.bind(this);
    this.filterPosts = this.filterPosts.bind(this);
    this.getPostsByPaginatedLink = this.getPostsByPaginatedLink.bind(this);
    this.createTagSlug = this.createTagSlug.bind(this);
    this.getPostsByTag = this.getPostsByTag.bind(this);
  }

  componentDidMount() {
    this.getPostsByStatus('accepted', this.props.campaign.id);
  }

  // Filter posts based on status or tag(s).
  filterPosts(filter) {
    // If the filter is a status, make API call to get posts by status.
    if (filter === 'Pending' || filter === 'Accepted' || filter === 'Rejected') {
      this.getPostsByStatus(filter.toLowerCase(), this.props.campaign.id);
    } else {
      // If the filter is a tag, make the API call to get posts by tag.
      var tagSlug = this.createTagSlug(filter);

      this.getPostsByTag(tagSlug, this.props.campaign.id);
    }

  }

  // Create tag slug
  createTagSlug(filter) {
    var splitFilter = filter.toLowerCase().split(' ');

    if (splitFilter[splitFilter.length-1] === '👻') {
      splitFilter.pop();
    }

    return splitFilter.join('-');
  }

  // Open the history modal of the given post
  // @TODO - Figure out how to share this logic between components so it
  // doesn't need to be duplicated between components.
  showHistory(postId, event) {
    event.preventDefault();

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

        var firstPostId = Object.keys(newState.posts)[0];
        newState.posts[firstPostId].signup.data.quantity = result.quantity;

        return newState;
      });
    });

    // Close the modal
    this.hideHistory();
  }

    // Updates a post status.
  updatePost(postId, fields) {
    fields.post_id = postId;

    let request = this.api.put('reviews', fields);

    request.then((result) => {
      this.setState((previousState) => {
        const newState = {...previousState};
        newState.posts[postId].status = fields.status;

        return newState;
      });
    });

  }

  // Tag a post.
  updateTag(postId, tag) {
    const fields = {
      post_id: postId,
      tag_name: tag,
    };

    let response = this.api.post('tags', fields);

    return response.then((result) => {
      this.setState((previousState) => {
        const newState = {...previousState};
        const user = newState.posts[postId].user;
        const signup = newState.posts[postId].signup.data;

        // Merge existing post with the newly updated values from API.
        newState.posts[postId] = {
          ...newState.posts[postId],
          ...result['data']
        };

        return newState;
      });
    });
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

  // Make API call to paginated link to get next/previous batch of posts.
  getPostsByPaginatedLink(url, event) {
    event.preventDefault();

    // Strip the url to get query parameters.
    let splitEndpoint = url.split('/');
    let path = splitEndpoint.slice(-1)[0];
    let queryString = (path.split('?'))[1];

    this.api.get('api/v2/posts', queryString)
    .then(json => this.setState({
      posts: keyBy(json.data, 'id'),
      postTotals: json.meta.pagination.total,
      displayHistoryModal: null,
      historyModalId: null,
      nextPage: json.meta.pagination.links.next,
      prevPage: json.meta.pagination.links.previous,
    }));
  }

  // Make API call to GET /posts to get posts by filtered status.
  getPostsByStatus(status, campaignId) {
    this.api.get('api/v2/posts', {
      filter: {
        status: status,
        campaign_id: campaignId,
      },
      include: 'signup,siblings',
    })
    .then(json => this.setState({
      posts: keyBy(json.data, 'id'),
      filter: status,
      postTotals: json.meta.pagination.total,
      displayHistoryModal: null,
      historyModalId: null,
      nextPage: json.meta.pagination.links.next,
      prevPage: json.meta.pagination.links.previous,
    }));
  }

  // Make API call to GET /posts to get posts by filtered tag.
  getPostsByTag(tagSlug, campaignId) {
    this.api.get('api/v2/posts', {
      filter: {
        tag: tagSlug,
        campaign_id: campaignId,
      },
      include: 'signup,siblings',
    })
    .then(json => this.setState({
      posts: keyBy(json.data, 'id'),
      postTotals: json.meta.pagination.total,
      displayHistoryModal: null,
      historyModalId: null,
      nextPage: json.meta.pagination.links.next,
      prevPage: json.meta.pagination.links.previous,
    }));
  }

  render() {
    const posts = this.state.posts;
    const campaign = this.props.campaign;

    return (
      <div className="container">
        <StatusCounter postTotals={this.props.post_totals} campaign={campaign} />

        <PostFilter onChange={this.filterPosts} />

        { map(posts, (post, key) => post.status === this.state.filter ? <InboxItem allowReview={true} onUpdate={this.updatePost} onTag={this.updateTag} showHistory={this.showHistory} deletePost={this.deletePost} key={key} post={post} campaign={campaign} signup={post.signup.data} /> : null) }

        <ModalContainer>
            {this.state.displayHistoryModal ?
              <HistoryModal id={this.state.historyModalId}
                onUpdate={this.updateQuantity}
                onClose={e => this.hideHistory(e)}
                campaign={campaign}
                signup={posts[this.state.historyModalId].signup.data}
              />
            : null}
        </ModalContainer>

        <PagingButtons onPaginate={this.getPostsByPaginatedLink} prev={this.state.prevPage} next={this.state.nextPage}></PagingButtons>
      </div>
    )
  }
}

export default CampaignSingle;
