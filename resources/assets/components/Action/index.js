import React from 'react';
import PropTypes from 'prop-types';
import { parse } from 'date-fns';

import './action.scss';

class Action extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const action = this.props.action;
    const campaign = this.props.campaign;

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
          <h4>CALLPOWER CAMPAIGN ID</h4>
          {action.callpower_campaign_id ? (
            <p>{action.callpower_campaign_id}</p>
          ) : (
            <p>-</p>
          )}
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
              {action.reportback === true ? (
                <p className="yes">Yes</p>
              ) : (
                <p className="no">No</p>
              )}
            </li>
            <li>
              <h4>CIVIC ACTION</h4>
              {action.civic_action === true ? (
                <p className="yes">Yes</p>
              ) : (
                <p className="no">No</p>
              )}
            </li>
            <li>
              <h4>SCHOLARSHIP</h4>
              {action.scholarship_entry === true ? (
                <p className="yes">Yes</p>
              ) : (
                <p className="no">No</p>
              )}
            </li>
          </ul>
        </div>
        <div className="container__row">
          <ul>
            <li>
              <h4>ANONYMOUS POST</h4>
              {action.anonymous === true ? (
                <p className="yes">Yes</p>
              ) : (
                <p className="no">No</p>
              )}
            </li>
          </ul>
        </div>
        {new Date(parse(campaign.start_date)) > Date.now() ? (
          <div className="container__row">
            <a
              className="button -secondary"
              href={`${campaign.id}/actions/${action.id}/edit`}
            >
              Edit Action
            </a>

            <button
              className="button delete -tertiary"
              onClick={e => this.props.deleteAction(action.id, e)}
            >
              Delete Action
            </button>
          </div>
        ) : null}
      </div>
    );
  }
}

Action.propTypes = {
  action: PropTypes.object.isRequired,
};

export default Action;
