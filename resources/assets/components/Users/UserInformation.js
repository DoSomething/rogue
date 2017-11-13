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

          {
            (() => {
              if (props.user.mobile) {
                if (props.linkSignup && isEmpty(props.user.first_name)) {
                  return (<span><a href={`/signups/${props.linkSignup}`}>{props.user.mobile}</a><br /></span>);
                }

                return (<span>{props.user.mobile}<br /></span>);
              }
              return (null);
            })()
          }

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
  // @TODO: For the `user` and `children` props sometimes they come
  // in as an array and sometimes as an object.
  // Figure out why and update the validation.
  user: PropTypes.oneOfType([
    PropTypes.array,
    PropTypes.object,
  ]),
  children: PropTypes.node,
  linkSignup: PropTypes.number,
};

UserInformation.defaultProps = {
  children: null,
  linkSignup: null,
  user: null,
};

export default UserInformation;
