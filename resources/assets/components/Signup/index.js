// Utilities
import React from 'react';
import { map, startCase, keyBy} from 'lodash';
import { RestApiClient } from '@dosomething/gateway';
import { calculateAge, displayName, displayCityState } from '../../helpers';

// Components
import Post from '../Post';
import Quantity from '../Quantity';
import TextBlock from '../TextBlock';
import HistoryModal from '../HistoryModal';
import UploaderModal from '../UploaderModal'
import ModalContainer from '../ModalContainer';
import MetaInformation from '../MetaInformation';
import UserInformation from '../Users/UserInformation';

class PostGroup extends React.Component {
  render() {
    return (
      <div className="container__row">
        <div className="container__block">
            <h2>{startCase(this.props.groupType)}</h2>
        </div>
        {
          map(this.props.posts, (post, key) => {
            if (post['status'] === this.props.groupType) {
              return <Post key={key} post={post} signup={this.props.signup} onUpdate={this.props.onUpdate} onTag={this.props.onTag} deletePost={this.props.deletePost} />;
            }
          })
        }
      </div>
    )
  }
}

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

    this.api = new RestApiClient;
    this.updatePost = this.updatePost.bind(this);
    this.updateTag = this.updateTag.bind(this);
    this.updateQuantity = this.updateQuantity.bind(this);
    this.showHistory = this.showHistory.bind(this);
    this.hideHistory = this.hideHistory.bind(this);
    this.deletePost = this.deletePost.bind(this);
    this.showUploader = this.showUploader.bind(this);
    this.hideUploader = this.hideUploader.bind(this);
    this.submitReportback = this.submitReportback.bind(this);
  }

  componentDidMount() {
    this.getUserActivity(this.props.signup_id);
  }

  /**
   * Gets the user activity for the specified user and update state.
   *
   * @param {String} id
   * @return {Object}
   */
  getUserActivity(id) {
    this.api.get('api/v2/activity', {
      filter: {
        id: id,
      }
    }).then(json => this.setState({
      signup: json.data[0],
      posts: keyBy(json.data[0].posts.data, 'id')
    }));
  }

  // Open the history modal of the given post
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

  // Open the uploader modal to upload a new post
  showUploader(campaign, event) {
    event.preventDefault();

    this.setState({
      displayUploaderModal: true,
      campaign: campaign,
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

        newState.signup.quantity = result.quantity;

        return newState;
      });
    });

    // Close the modal
    this.hideHistory();
  }

  // Delete a post.
  deletePost(postId, event) {
    event.preventDefault();
    const confirmed = confirm('🚨🔥🚨 Are you sure you want to delete this? 🚨🔥🚨');

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

  // Submit a new reportback on behalf of the user.
  submitReportback(reportback) {
    // Fields to send to /posts
    const fields = {
      northstar_id: reportback.northstarId,
      campaign_id: reportback.campaignId,
      campaign_run_id: reportback.campaignRunId,
      quantity: reportback.impact,
      why_participated: reportback.whyParticipated,
      caption: reportback.caption,
      source: reportback.source,
      status: reportback.status,
      file: reportback.media.dataURL,
      remote_addr: '',
    };

    // Make API request to Rogue to upload post
    let request = this.api.post('posts', fields);

    request.then((result) => {
      // Update the state
      this.setState((previousState) => {
        const newState = {...previousState};
        const post = keyBy(result,'id');
        const key = Number(Object.keys(post));

        newState.successfulSubmission = {
          success: {
            message: "Thanks for the photo! It has been automatically approved.",
          }
        };

        newState.posts[key] = post[key];

        return newState;
      });
    })
    .catch(error =>
      this.setState((previousState) => {
        const newState = {...previousState};

        newState.successfulSubmission = {
          error: {
            message: "Oops, looks like something went wrong.",
          }
        };

        return newState;
      })
    );
  }

  render() {
    const user = this.props.user;
    const signup = this.state.signup;
    const campaign = this.props.campaign;
    const posts = this.state.posts;

    return (
      <div className="signup">
        <div className="container__row">
          <div className="container__block -half">
            <UserInformation user={user}>
              <TextBlock title="Why Statement" content={signup.why_participated} />
            </UserInformation>
          </div>

          <div className="container__block -half">
            <Quantity quantity={signup.quantity} noun={campaign.reportback_info.noun} verb={campaign.reportback_info.verb} />

            <div className="container__row">

              <a href="#" onClick={e => this.showHistory(signup['signup_id'], e)}>Edit | Show History</a>

              <ModalContainer>
                {this.state.displayHistoryModal ?
                  <HistoryModal id={this.state.historyModalId}
                    onUpdate={this.updateQuantity}
                    onClose={e => this.hideHistory(e)}
                    signup={signup}
                    campaign={campaign}
                  />
                : null}
              </ModalContainer>
            </div>

            <div className="container__row">
              <a href="#" onClick={e => this.showUploader(signup, e)}>Upload Photo</a>

              <ModalContainer>
                {this.state.displayUploaderModal ?
                  <UploaderModal
                    onClose={e => this.hideUploader(e)}
                    signup={signup}
                    campaign={campaign}
                    submitReportback={this.submitReportback}
                    success={this.state.successfulSubmission}
                  />
                : null}
              </ModalContainer>
            </div>

            <MetaInformation title="Meta" details={{
              "Signup ID": signup.signup_id,
              "Northstar ID": user.id,
              "Signup Source": signup.signup_source,
              "Created At": new Date(signup.created_at).toDateString()
            }} />
          </div>
        </div>
        {
          map(['pending', 'accepted', 'rejected'], (status, key) => {
            return <PostGroup key={key} groupType={status} posts={posts} signup={signup} onUpdate={this.updatePost} onTag={this.updateTag} deletePost={this.deletePost} />
          })
        }
      </div>
    )
  }
}

export default Signup;
