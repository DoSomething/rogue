/* global FormData */

// Utilities
import React from 'react';
import PropTypes from 'prop-types';
import RogueClient from '../../utilities/RogueClient';
import { map, forEach, startCase, keyBy, filter, isEmpty } from 'lodash';
import { RestApiClient } from '@dosomething/gateway';

// Components
import Post from '../Post';
import Empty from '../Empty';
import Quantity from '../Quantity';
import TextBlock from '../TextBlock';
// @TODO: add this back in when we are ready to show history modal.
// import HistoryModal from '../HistoryModal';
import UploaderModal from '../UploaderModal';
import ModalContainer from '../ModalContainer';
import MetaInformation from '../MetaInformation';
import UserInformation from '../Users/UserInformation';

class PostGroup extends React.Component {
  getPostsByStatus(status) {
    const posts = filter(this.props.posts, post => post.status === this.props.groupType);

    return posts;
  }

  render() {
    const posts = this.getPostsByStatus(this.props.groupType);

    return (
      <div className="container__row">
        <div className="container__block">
          <h2 className="heading -emphasized -padded"><span>{startCase(this.props.groupType)}</span></h2>
        </div>

        {
          ! isEmpty(posts) ?
            map(posts, (post, key) => (<Post
              key={key}
              post={post}
              displayUser={false}
              signup={this.props.signup}
              onUpdate={this.props.onUpdate}
              onTag={this.props.onTag}
              deletePost={this.props.deletePost}
              showSiblings={false}
              campaign={this.props.campaign}
              rotate={this.props.rotate}
              showQuantity
            />))
            :
            <Empty header={`This user has no ${this.props.groupType} posts`} />
        }
      </div>
    );
  }
}

PostGroup.propTypes = {
  posts: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
  signup: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
  onUpdate: PropTypes.func.isRequired,
  onTag: PropTypes.func.isRequired,
  deletePost: PropTypes.func.isRequired,
  groupType: PropTypes.string.isRequired,
};


class Signup extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      signup: {},
      posts: {},
      displayHistoryModal: false,
      historyModalId: null,
      displayUploaderModal: false,
    };

    this.api = new RogueClient(window.location.origin, {
        headers: {
          'Authorization' : `Bearer ${window.AUTH}`,
        },
    });

    this.updatePost = this.updatePost.bind(this);
    this.updateTag = this.updateTag.bind(this);
    this.updateQuantity = this.updateQuantity.bind(this);
    // @TODO: add this back in when we are ready to show history modal.
    // this.showHistory = this.showHistory.bind(this);
    // this.hideHistory = this.hideHistory.bind(this);
    this.deletePost = this.deletePost.bind(this);
    this.deleteSignup = this.deleteSignup.bind(this);
    this.showUploader = this.showUploader.bind(this);
    this.hideUploader = this.hideUploader.bind(this);
    this.submitPost = this.submitPost.bind(this);
    this.updateSignup = this.updateSignup.bind(this);
  }

  componentDidMount() {
    this.getSignupAndPosts(this.props.signup_id);
  }

  /**
   * Gets the signup and posts and update state.
   *
   * @param {String} id
   * @return {Object}
   */
  getSignupAndPosts(id) {
    this.api.get(`api/v3/signups/${id}`, {
      include: 'posts',
    }).then(json => this.setState({
      signup: json.data,
      posts: keyBy(json.data.posts.data, 'id'),
    }));
  }

  // @TODO: add this back in when we are ready to show history modal.
  // Open the history modal of the given post
  // showHistory(postId, event) {
  //   event.preventDefault();

  //   this.api.get('api/v2/events', {
  //     filter: {
  //       signup_id: this.props.signup_id,
  //     },
  //   }).then((result) => {
  //     this.setState((previousState) => {
  //       const newState = { ...previousState };

  //       newState.displayHistoryModal = true;
  //       newState.historyModalId = postId;
  //       newState.signupEvents = Object.values(result.data);

  //       return newState;
  //     });
  //   });
  // }

  // Close the open history modal
  // hideHistory(event) {
  //   if (event) {
  //     event.preventDefault();
  //   }

  //   this.setState({
  //     displayHistoryModal: false,
  //     historyModalId: null,
  //   });
  // }

  // Open the uploader modal to upload a new post
  showUploader(campaign, event) {
    event.preventDefault();

    this.setState({
      displayUploaderModal: true,
      campaign,
    });
  }

  // Close the open uploader modal
  hideUploader(event) {
    if (event) {
      event.preventDefault();
    }

    this.setState({
      displayUploaderModal: false,
      campaign: null,
    });
  }

  // Updates a post status.
  updatePost(post, fields) {
    fields.post_id = post.id;

    let request = this.api.postReview(fields);

    request.then((result) => {
      this.setState((previousState) => {
        const newState = { ...previousState };

        newState.posts[post.id].status = fields.status;

        return newState;
      });
    });
  }

  // Tag a post.
  updateTag(postId, tag) {
    const field = {
      tag_name: tag,
    };

    // Check to see if we are creating or deleting this tag.
    const response = this.api.post(`api/v3/posts/${postId}/tags`, field);

    return response.then((result) => {
      this.setState((previousState) => {
        const newState = { ...previousState };

        newState.posts[postId] = result.data;

        return newState;
      });
    });
  }

  // Update a signups quantity.
  updateQuantity(signup, newQuantity) {
    // Fields to send to /posts
    const fields = {
      northstar_id: signup.northstar_id,
      campaign_id: signup.campaign_id,
      campaign_run_id: signup.campaign_run_id,
      quantity: newQuantity,
    };

    // Make API request to Rogue to update the quantity on the backend
    const request = this.api.post('api/v3/posts', fields);

    request.then((result) => {
      // Update the state
      this.setState((previousState) => {
        const newState = { ...previousState };
        newState.signup.quantity = result.quantity;

        return newState;
      });
    });

    // Close the modal
    this.hideHistory();
  }

  // Set newstate with updated quantity and why_participated.
  updateSignup(signup) {
    this.setState((previousState) => {
      const newState = { ...previousState };

      newState.signup.quantity = signup.quantity;
      newState.signup.why_participated = signup.why_participated;

      return newState;
    });
  }

  // Delete a post.
  deletePost(postId, event) {
    event.preventDefault();
    const confirmed = confirm('🚨🔥🚨 Are you sure you want to delete this? 🚨🔥🚨');

    if (confirmed) {
      // Make API request to Rogue to update the quantity on the backend
      const response = this.api.delete(`api/v3/posts/${postId}`);

      response.then((result) => {
        // Update the state
        this.setState((previousState) => {
          const newState = { ...previousState };

          // Remove the deleted post from the state
          delete (newState.posts[postId]);

          // Return the new state
          return newState;
        });
      });
    }
  }

  // Delete a signup.
  deleteSignup(signupId, event) {
    event.preventDefault();
    const confirmed = confirm('🚨🔥🚨 Are you sure you want to delete this? 🚨🔥🚨');

    if (confirmed) {
      // Make API request to Rogue to delete the signup on the backend.
      const response = this.api.delete(`api/v3/signups/${signupId}`);

      response.then((result) => {
        // Update the state.
        this.setState((previousState) => {
          const newState = { ...previousState };

          // Set the deleted signup to undefined.
          newState.signup = undefined;

          // Return the new state
          return newState;
        });
      });
    }
  }

  // @TODO: make this work for any type of post?
  // Submit a new photo post on behalf of the user.
  submitPost(post) {
    // To submit a post with a file, we need to change the Content-Type to muultipart/form-data.
    const api = new RestApiClient(window.location.origin, {
        headers: {
          'Authorization' : `Bearer ${window.AUTH}`,
          'Content-Type': 'multipart/form-data',
        },
    });

    // Fields to send to /v3/posts
    const fields = {
      northstar_id: post.northstarId,
      campaign_id: post.campaignId,
      campaign_run_id: post.campaignRunId,
      quantity: post.quantity,
      why_participated: post.whyParticipated,
      text: post.text,
      status: post.status,
      file: post.media.file,
      type: 'photo',
      // @TODO: add action to the form as an optional field?
      action: 'default',
    };

    const payload = new FormData();
    forEach(fields, (value, key) => payload.append(key, value));
    // Make API request to Rogue to upload post
    const request = api.post('api/v3/posts', payload);

    request.then((result) => {
      // Update the state
      this.setState((previousState) => {
        const newState = { ...previousState };
        const post = keyBy(result, 'id');
        const key = Number(Object.keys(post));

        newState.successfulSubmission = {
          success: {
            message: 'Thanks for the photo! It has been automatically approved.',
          },
        };

        newState.posts[key] = post[key];

        return newState;
      });
    })
      .catch(error =>
        this.setState((previousState) => {
          const newState = { ...previousState };

          newState.successfulSubmission = {
            error: {
              message: 'Oops, looks like something went wrong.',
            },
          };

          return newState;
        }),
      );
  }

  render() {
    const user = this.props.user;
    const signup = this.state.signup;
    const campaign = this.props.campaign;
    const posts = this.state.posts;

    if (! this.state.signup) {
      return <Empty header={`This signup has been deleted`} />;
    }

    return (
      <div className="signup">
        <div className="container__row">
          <div className="container__block -half">
            <UserInformation user={user}>
              <TextBlock title="Why Statement" content={signup.why_participated} />
            </UserInformation>
          </div>

          <div className="container__block -half">
            {/* @TODO: add this back in when we're ready to show history modal.
            <Quantity quantity={signup.quantity} noun={campaign.reportback_info.noun} verb={campaign.reportback_info.verb} />
            <div className="container__row">

              <a href="#" onClick={e => this.showHistory(signup.signup_id, e)}>Edit | Show History</a>

              <ModalContainer>
                {this.state.displayHistoryModal ?
                  <HistoryModal
                    id={this.state.historyModalId}
                    onUpdate={this.updateQuantity}
                    onClose={e => this.hideHistory(e)}
                    signup={signup}
                    campaign={campaign}
                    signupEvents={this.state.signupEvents}
                  />
                  : null}
              </ModalContainer>
            </div>
            */}
            <div className="container__row">
              <a href="#" onClick={e => this.showUploader(signup, e)}>Upload Photo</a>

              <ModalContainer>
                {this.state.displayUploaderModal ?
                  <UploaderModal
                    onClose={e => this.hideUploader(e)}
                    signup={signup}
                    campaign={campaign}
                    submitPost={this.submitPost}
                    updateSignup={this.updateSignup}
                    success={this.state.successfulSubmission}
                  />
                  : null}
              </ModalContainer>
            </div>

            <div className="container__row">
              <button className="button delete -tertiary" onClick={e => this.deleteSignup(signup.id, e)}>Delete Signup</button>
            </div>

            <MetaInformation
              title="Meta"
              details={{
                'Signup ID': signup.id,
                'Northstar ID': user.id,
                'Signup Source': signup.source,
                'Created At': new Date(signup.created_at).toDateString(),
              }}
            />
          </div>
        </div>
        {
          map(['pending', 'accepted', 'rejected'], (status, key) => <PostGroup key={key} groupType={status} posts={posts} signup={signup} onUpdate={this.updatePost} onTag={this.updateTag} deletePost={this.deletePost} campaign={campaign} />)
        }
      </div>
    );
  }
}

Signup.propTypes = {
  signup_id: PropTypes.number.isRequired,
  user: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
  campaign: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
  rotate: PropTypes.func.isRequired,
};

export default Signup;
