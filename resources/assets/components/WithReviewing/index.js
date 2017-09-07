import React from 'react';
import { keyBy } from 'lodash';
import { RestApiClient } from '@dosomething/gateway';
import { extractPostsFromSignups } from '../../helpers';

const reviewComponent = (Component, data) => {
  return class extends React.Component {
    constructor(props) {
      super(props);

      this.state = {
        signups: keyBy(data.signups, 'id'),
        posts: extractPostsFromSignups(data.signups),
        users: data.users,
        campaign: data.campaign,
        displayHistoryModal: false,
        historyModalId: null,
      };

      this.api = new RestApiClient;
      this.updatePost = this.updatePost.bind(this);
      this.updateTag = this.updateTag.bind(this);
      this.updateQuantity = this.updateQuantity.bind(this);
      this.showHistory = this.showHistory.bind(this);
      this.hideHistory = this.hideHistory.bind(this);
      this.deletePost = this.deletePost.bind(this);
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

          newState.posts[postId] = result['data'];

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
      const methods = {
        updatePost: this.updatePost,
        updateTag: this.updateTag,
        updateQuantity: this.updateQuantity,
        showHistory: this.showHistory,
        hideHistory: this.hideHistory,
        deletePost: this.deletePost,
      };

      return <Component {...this.state} {...methods} />;
    }
  }
}

export default reviewComponent;

