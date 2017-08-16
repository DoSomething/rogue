// Utilities
import React from 'react';
import { map } from 'lodash';
import { RestApiClient } from '@dosomething/gateway';
import { calculateAge, displayName, displayCityState } from '../../helpers';

// Components
import InboxItem from '../InboxItem';
import HistoryModal from '../HistoryModal';
import ModalContainer from '../ModalContainer';
import UserInformation from '../Users/UserInformation';

// Styles
import './signup.scss';


class Signup extends React.Component {
  constructor(props) {
    super(props);


    this.state = {
      displayHistoryModal: false,
      historyModalId: null,
    };

    this.api = new RestApiClient;
    this.showHistory = this.showHistory.bind(this);
    this.hideHistory = this.hideHistory.bind(this);
  }

  // Open the history modal of the given post
  showHistory(id, event) {
    event.preventDefault()

    this.setState({
      displayHistoryModal: true,
      historyModalId: id,
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

  render() {
    const user = this.props.user;
    const signup = this.props.signup;
    const campaign = this.props.campaign;
    const posts = this.props.signup.posts;

    return (
      <div className="signup">
        <div className="container__row">
          <div className="container__block -half">
            <UserInformation user={user} includeMeta={false} />

            <div className="container__block">
              <h4 className="heading">Why Statement</h4>
              <p>{signup.why_participated}</p>
            </div>
          </div>

          <div className="container__block -half">
            <div className="container__block">

              <a href="#" onClick={e => this.showHistory(signup['signup_id'], e)}>Edit | Show History</a>

              <ModalContainer>
                {this.state.displayHistoryModal ? <HistoryModal id={this.state.historyModalId} onUpdate={this.updateQuantity} onClose={e => this.hideHistory(e)} details={{campaign: campaign, signups: this.state.signups }}/> : null}
              </ModalContainer>
            </div>

            <div className="container__block meta">
              <h4 className="heading">Meta</h4>
              <p>
                <span>Signup ID: {signup.id}<br/></span>
                <span>Northstar ID: {user.id}<br/></span>
                <span>Signup Source: {signup.source}<br/></span>
                <span>Created At: {new Date(signup.created_at).toDateString()}<br/></span>
              </p>
            </div>
          </div>
        </div>

        <div className="container__row">
            <h2>Accepted</h2>
            {
              map(posts, (post, key) => {
                if (post['status'] === 'accepted') {
                  return <InboxItem allowReview={true} key={key} post={post} campaign={campaign} signup={signup} />;
                }
              })
            }
        </div>

        <div className="container__row">
            <h2>Pending</h2>
            {
              map(posts, (post, key) => {
                if (post['status'] === 'pending') {
                  return <InboxItem allowReview={true} key={key} post={post} campaign={campaign} signup={signup} />;
                }
              })
            }
        </div>

        <div className="container__row">
            <h2>Rejected</h2>
            {
              map(posts, (post, key) => {
                if (post['status'] === 'rejected') {
                  return <InboxItem allowReview={true} key={key} post={post} campaign={campaign} signup={signup} />;
                }
              })
            }
        </div>
      </div>
    )
  }
}

export default Signup;
