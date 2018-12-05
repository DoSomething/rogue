import React from 'react';
import PropTypes from 'prop-types';

import './user-export.scss';

const UserExport = props => (
  <div className="user-export">
    <h1 className="heading -delta">User Export</h1>
    <p>
      Generate a .csv of all users signed via web and who have opted in to
      receive additional messaging. The generated file will be emailed to you
      when complete.
    </p>
    <div>
      <a className="button -secondary" href={`/exports/${props.campaign.id}`}>
        Export
      </a>
    </div>
  </div>
);

UserExport.propTypes = {
  campaign: PropTypes.shape({
    id: PropTypes.number,
  }).isRequired,
};

export default UserExport;
