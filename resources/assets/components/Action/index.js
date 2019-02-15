import React from 'react';
import PropTypes from 'prop-types';

import './action.scss';

class Action extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const action = this.props.action;

    return (
      <div className="container__action">
        <div className="container__row">
          <h2>{action.name}</h2>
        </div>
        <div className="container__row">
          <h4>ID</h4>
          <p>{action.id}</p>
        </div>
        <div className="container__row">
          <ul>
            <li>
              <h4>POST TYPE</h4>
              <p>{action.post_type}</p>
            </li>
            <li>
              <h4>NOUN</h4>
              <p>{action.noun}</p>
            </li>
            <li>
              <h4>VERB</h4>
              <p>{action.verb}</p>
            </li>
          </ul>
        </div>
        <div className="container__row">
          <ul>
            <li>
              <h4>REPORTBACK</h4>
              {action.reportback === 1 ? (
                <p className="yes">Yes</p>
              ) : (
                <p className="no">No</p>
              )}
            </li>
            <li>
              <h4>CIVIC ACTION</h4>
              {action.civic_action === 1 ? (
                <p className="yes">Yes</p>
              ) : (
                <p className="no">No</p>
              )}
            </li>
            <li>
              <h4>SCHOLARHIP</h4>
              {action.scholarship_entry === 1 ? (
                <p className="yes">Yes</p>
              ) : (
                <p className="no">No</p>
              )}
            </li>
          </ul>
        </div>
      </div>
    );
  }
}

Action.propTypes = {
  action: PropTypes.object.isRequired,
};

export default Action;
