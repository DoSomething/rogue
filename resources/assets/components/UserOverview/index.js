import React from 'react';
import { calculateAge, displayName, displayCityState } from '../../helpers';

class UserOverview extends React.Component {
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
      </div>
    )
  }
}

export default UserOverview;
