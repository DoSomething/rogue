import React from 'react';
import { isEmpty } from 'lodash';
import PropTypes from 'prop-types';

/* eslint-disable react/prop-types */

/**
 * Returns a readable City and State string.
 *
 * @param {Object} user
 * @return {ReactElement|null}
 */
const UserLocation = ({ user }) => {
  if (!user || !user.addr_city || !user.addr_state) {
    return null;
  }

  return (
    <span>
      {user.addr_city}, {user.addr_state}
      <br />
    </span>
  );
};

/**
 * Returns a readable display name and age (if provided).
 *
 * @param {Object} user
 * @return {ReactElement}
 */
const UserName = ({ user, link }) => {
  let displayName = user.display_name || 'N/A';

  if (user.age) {
    displayName += `, ${user.age}`;
  }

  if (link) {
    return <a href={link}>{displayName}</a>;
  }

  return <span>{displayName}</span>;
};

const UserInformation = props => (
  <div>
    {!isEmpty(props.user) ? (
      <div className="container -padded">
        <h2 className="heading">
          <UserName
            user={props.user}
            link={props.linkSignup ? `/signups/${props.linkSignup}` : null}
          />
        </h2>
        <p>
          {props.user.email_preview ? (
            <span>
              {props.user.email_preview}
              <br />
            </span>
          ) : null}

          {props.user.mobile_preview ? (
            <span>
              {props.user.mobile_preview}
              <br />
            </span>
          ) : null}

          <UserLocation user={props.user} />
        </p>
      </div>
    ) : null}

    {props.children}
  </div>
);

UserInformation.propTypes = {
  // @TODO: For the `user` and `children` props sometimes they come
  // in as an array and sometimes as an object.
  // Figure out why and update the validation.
  user: PropTypes.oneOfType([PropTypes.array, PropTypes.object]),
  children: PropTypes.node,
  linkSignup: PropTypes.number,
};

UserInformation.defaultProps = {
  children: null,
  linkSignup: null,
  user: null,
};

export default UserInformation;
