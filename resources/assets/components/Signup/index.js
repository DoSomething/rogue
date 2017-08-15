import React from 'react';
import UserInformation from '../Users/UserInformation';
import { calculateAge, displayName, displayCityState } from '../../helpers';

class Signup extends React.Component {
  render() {
    const user = this.props.user;
    const signup = this.props.signup;
    const campaign = this.props.campaign;

    return (
      <div>
        <UserInformation user={user} includeMeta={false} />

        <div className="container__block">
          <h4 className="heading">Why Statement</h4>
          <p>{signup.why_participated}</p>
        </div>
      </div>
    )
  }
}

export default Signup;
