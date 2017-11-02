import React from 'react';
import { isEmpty } from 'lodash';
import PropTypes from 'prop-types';
import { displayUserInfo, displayCityState } from '../../helpers';

const UserInformation = props => (
  <div>
    {! isEmpty(props.user) ?
      <div className="container -padded">
        {props.linkSignup ?
          <h2 className="heading"><a href={`/signups/${props.linkSignup}`}>{displayUserInfo(props.user.first_name, props.user.last_name, props.user.birthdate)}</a></h2>
          :
          <h2 className="heading">{displayUserInfo(props.user.first_name, props.user.last_name, props.user.birthdate)}</h2>
        }
        <p>
          {props.user.email ? <span>{props.user.email}<br /></span> : null}
          {props.user.mobile ? <span>{props.user.mobile}<br /></span> : null }
          {displayCityState(props.user.addr_city, props.user.addr_state) ?
            <span>{displayCityState(props.user.addr_city, props.user.addr_state) }<br /></span>
            :
            null
          }
        </p>
      </div>
      : null
    }

    {props.children}
  </div>
);

UserInformation.propTypes = {
  user: PropTypes.shape({
    first_name: PropTypes.string,
    last_name: PropTypes.string,
    birthdate: PropTypes.string,
    email: PropTypes.string,
    mobile: PropTypes.string,
    addr_city: PropTypes.string,
    addr_state: PropTypes.string,
  }).isRequired,
  children: PropTypes.string,
  linkSignup: PropTypes.int,
};

UserInformation.defaultProps = {
  children: null,
  linkSignup: null,
};

export default UserInformation;
