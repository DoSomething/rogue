import React from 'react';
import { map } from 'lodash';
import { calculateAge, displayName, displayCityState } from '../../helpers';

const UserInformation = (props) => (
  <div>
    <div className="container -padded">
      <h2 className="heading">{displayName(props.user.first_name, props.user.last_name)}, {calculateAge(props.user.birthdate)}</h2>
      <p>
        {props.user.email ? <span>{props.user.email}<br/></span>: null}
        {props.user.mobile ? <span>{props.user.mobile}<br/></span> : null }
        {displayCityState(props.user.addr_city, props.user.addr_state) ? <span>{displayCityState(props.user.addr_city, props.user.addr_state) }<br/></span> : null }
      </p>
    </div>

    {props.includeMeta ?
      <div className="container">
        {props.meta.title ? <h4 className="heading">{props.meta.title}</h4> : null}
        {
          map(props.meta.details, (item, key) => {
            return <span key={key}>{item}<br/></span>
          })
        }
      </div>
      : null }
  </div>
);


export default UserInformation;
