import React from 'react';
import { calculateAge, displayName } from '../../helpers';

class UserOverview extends React.Component {
  render() {
    const user = this.props.user;

    return (
      <div>
        <div className="container__block">
          <h2 className="heading -emphasized -padded"><span>User Info</span></h2>
        </div>
        <div className="container__block">
          <h2 className="heading">{displayName(user.first_name, user.last_name)}, {calculateAge(user.birthdate)}</h2>
        </div>
      </div>
    )
  }
}

export default UserOverview;
