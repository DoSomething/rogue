import React from 'react';
import { map } from 'lodash';
import { RestApiClient} from '@dosomething/gateway';
import { extractSignupsFromPosts } from '../../helpers';

import Post from '../Post';
import PostFilter from '../PostFilter';
import HistoryModal from '../HistoryModal';
import PagingButtons from '../PagingButtons';
import StatusCounter from '../StatusCounter';
import ModalContainer from '../ModalContainer';

class CampaignSingle extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      loadingNewPosts: false
    };

    this.api = new RestApiClient;
    this.filterPosts = this.filterPosts.bind(this);
    this.getPostsByTag = this.getPostsByTag.bind(this);
    this.getPostsByStatus = this.getPostsByStatus.bind(this);
    this.getPostsByPaginatedLink = this.getPostsByPaginatedLink.bind(this);
  }

  // Filter posts based on status or tag(s).
  filterPosts(filter) {
    // If the filter is a status, make API call to get posts by status.
    if (['pending', 'accepted', 'rejected'].includes(filter)) {
      this.getPostsByStatus(filter, this.props.campaign.id);
    } else {
      // If the filter is a tag, make the API call to get posts by tag.
      this.getPostsByTag(filter, this.props.campaign.id);
    }

  }

  // Make API call to paginated link to get next/previous batch of posts.
  getPostsByPaginatedLink(url, event) {
    event.preventDefault();

    this.setState({ loadingNewPosts: true });

    // Strip the url to get query parameters.
    let splitEndpoint = url.split('/');
    let path = splitEndpoint.slice(-1)[0];
    let queryString = (path.split('?'))[1];

    this.api.get('api/v2/posts', queryString)
    .then(json => {
      this.setState({loadingNewPosts: false });
      this.props.setNewPosts(json);
    });
  }

  // Make API call to GET /posts to get posts by filtered status.
  getPostsByStatus(status, campaignId) {
    this.setState({ loadingNewPosts: true });

    this.api.get('api/v2/posts', {
      filter: {
        status: status,
        campaign_id: campaignId,
      },
      include: 'signup,siblings',
    })
    .then(json => {
      this.setState({loadingNewPosts: false });
      this.props.setNewPosts(json);
    });
  }

  // Make API call to GET /posts to get posts by filtered tag.
  getPostsByTag(tagSlug, campaignId) {
    this.setState({ loadingNewPosts: true });

    this.api.get('api/v2/posts', {
      filter: {
        tag: tagSlug,
        campaign_id: campaignId,
      },
      include: 'signup,siblings',
    })
    .then(json => {
      this.setState({loadingNewPosts: false });
      this.props.setNewPosts(json);
    });
  }

  render() {
    const posts = this.props.posts;
    const campaign = this.props.campaign;
    const signups = this.props.signups;

    return (
      <div className="container">
        <StatusCounter postTotals={this.props.post_totals} campaign={campaign} />

        <PostFilter onChange={this.filterPosts} />

        {this.props.loading || this.state.loadingNewPosts ?
          <div className="spinner"></div>
        :
          map(posts, (post, key) =>
            <Post key={key}
              post={post}
              user={signups[post.signup_id].user.data}
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

        <PagingButtons onPaginate={this.getPostsByPaginatedLink} prev={this.props.prevPage} next={this.props.nextPage}></PagingButtons>
      </div>
    )
  }
}

export default CampaignSingle;
