// Utilities
import React from 'react';
import { map } from 'lodash';
import { RestApiClient } from '@dosomething/gateway';
import { calculateAge, displayName, displayCityState } from '../../helpers';

// Components
import InboxItem from '../InboxItem';
import Post from '../Post';
import WhyStatement from './WhyStatement';
import HistoryModal from '../HistoryModal';
import ModalContainer from '../ModalContainer';
import MetaInformation from '../MetaInformation';
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
            <UserInformation user={user}>
              <WhyStatement statement={signup.why_participated} />
            </UserInformation>
          </div>

          <div className="container__block -half">
            <div className="container__row">

              <a href="#" onClick={e => this.showHistory(signup['signup_id'], e)}>Edit | Show History</a>

              <ModalContainer>
                {this.state.displayHistoryModal ? <HistoryModal id={this.state.historyModalId} onUpdate={this.updateQuantity} onClose={e => this.hideHistory(e)} details={{campaign: campaign, signups: this.state.signups }}/> : null}
              </ModalContainer>
            </div>

            <MetaInformation title="Meta" details={{
              "Signup ID": signup.id,
              "Northstar ID": user.id,
              "Signup Source": signup.source,
              "Created At": new Date(signup.created_at).toDateString()
            }} />
          </div>
        </div>

        <div className="container__row">
          <div className="container__block">
              <h2>Accepted</h2>
          </div>
          {
            map(posts, (post, key) => {
              if (post['status'] === 'accepted') {
                return <Post key={key} post={post} />;
              }
            })
          }
        </div>

        <div className="container__row">
          <div className="container__block">
            <h2>Pending</h2>
          </div>
          {
            map(posts, (post, key) => {
              if (post['status'] === 'pending') {
                return <InboxItem allowReview={true} key={key} post={post} campaign={campaign} signup={signup} />;
              }
            })
          }
        </div>

        <div className="container__row">
          <div className="container__block">
            <h2>Rejected</h2>
          </div>
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
