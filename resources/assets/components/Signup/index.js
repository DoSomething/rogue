import React from 'react';
import './signup.scss';
import { RestApiClient } from '@dosomething/gateway';
import UserInformation from '../Users/UserInformation';
import { calculateAge, displayName, displayCityState } from '../../helpers';

import ModalContainer from '../ModalContainer';
import HistoryModal from '../HistoryModal';

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

    return (
      <div className="signup">
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
    )
  }
}

export default Signup;
