import React from 'react';
import { map, keyBy } from 'lodash';
import SignupCard from '../SignupCard';
import { RestApiClient } from '@dosomething/gateway';
import { calculateAge, displayName, displayCityState } from '../../helpers';

class UserOverview extends React.Component {
  constructor(props) {
    super(props);

    this.state = {},

    this.api = new RestApiClient;
  }

  componentDidMount() {
    this.getUserActivity(this.props.user.id);
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
        northstar_id: id,
      }
    }).then(json => this.setState({
      signups: json.data
    }, () => {
      // After we grab the signups, get the campaign objects for each signup.
      const ids = map(this.state.signups, 'campaign_id');
      this.getCampaigns(ids);
    }));
  }

  /**
   * Gets campaigns associated with signups.
   *
   * @param {Array} ids
   * @return {Object}
   */
  getCampaigns(ids) {
    this.api.get('api/v2/campaigns', {
      ids: ids.join()
    }).then(json => this.setState({
      campaigns: keyBy(json, 'id'),
    }));
  }

  render() {
    const user = this.props.user;
    const cityState = displayCityState(user.addr_city, user.addr_state);
    const name = displayName(user.first_name, user.last_name);

    return (
      <div>
        <div className="container__block">
          <h2 className="heading -emphasized -padded"><span>User Info</span></h2>
        </div>
        <div className="container__block">
          <h2 className="heading">{name}, {calculateAge(user.birthdate)}</h2>
          <p>
            {user.email ? <span>{user.email}<br/></span>: null}
            {user.mobile ? <span>{user.mobile}<br/></span> : null }
            {cityState ? <span>{cityState}<br/></span> : null }
          </p>
        </div>
        <div className="container__block">
          <h4 className="heading">Meta</h4>
          <p>
            <span>Source: {user.source}<br/></span>
            <span>Northstar ID: {user.id}<br/></span>
          </p>
        </div>
        <div className="container__block">
          <h2 className="heading -emphasized -padded"><span>Campaigns</span></h2>
        </div>

        <div className="container__block">
          {this.state.campaigns ?
            map(this.state.signups, (signup, index) => {
              return <SignupCard key={index} signup={signup} campaign={this.state.campaigns[signup.campaign_id]}/>;
            })
          : <div className="spinner"></div> }
        </div>
      </div>
    )
  }
}

export default UserOverview;
