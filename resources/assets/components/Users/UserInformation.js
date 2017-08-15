import React from 'react';
import { calculateAge, displayName, displayCityState } from '../../helpers';

export default (props) => (
  <div>
    <div className="container__block">
      <h2 className="heading">{displayName(props.user.first_name, props.user.last_name)}, {calculateAge(props.user.birthdate)}</h2>
      <p>
        {props.user.email ? <span>{props.user.email}<br/></span>: null}
        {props.user.mobile ? <span>{props.user.mobile}<br/></span> : null }
        {displayCityState(props.user.addr_city, props.user.addr_state) ? <span>{displayCityState(props.user.addr_city, props.user.addr_state) }<br/></span> : null }
      </p>
    </div>
    {props.includeMeta ?
      <div className="container__block">
        <h4 className="heading">Meta</h4>
        <p>
          <span>Source: {props.user.source}<br/></span>
          <span>Northstar ID: {props.user.id}<br/></span>
        </p>
      </div>
      : null }
  </div>
);
