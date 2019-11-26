import React from 'react';
import PropTypes from 'prop-types';

import './action.scss';

class Action extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    const action = this.props.action;
    const campaign = this.props.campaign;
    const isAdmin = window.AUTH.role == 'admin';

    return (
      <div className="container__action">
        {!this.props.isPermalink ? (
          <div className="container__row">
            <h2>
              <a href={`/actions/${action.id}`}>{action.name}</a>
            </h2>
          </div>
        ) : null}
        <div className="container__row">
          <ul>
            <li>
              <h4>ACTION ID</h4>
              <p>{action.id}</p>
            </li>
            {action.post_type === 'phone-call' ? (
              <li>
                <h4>CALLPOWER ID</h4>
                <p>{action.callpower_campaign_id}</p>
              </li>
            ) : null}
          </ul>
        </div>
        <div className="container__row">
          <ul>
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
              <h4>POST TYPE</h4>
              <p>{action.post_label}</p>
            </li>
            <li>
              <h4>ACTION TYPE</h4>
              <p>{action.action_label}</p>
            </li>
            <li>
              <h4>TIME COMMITMENT</h4>
              <p>{action.time_commitment_label}</p>
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
            <li>
              <h4>ONLINE ACTION</h4>
              {action.online === true ? (
                <p className="yes">Yes</p>
              ) : (
                <p className="no">No</p>
              )}
            </li>
            <li>
              <h4>QUIZ ACTION</h4>
              {action.quiz === true ? (
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
              <h4>COLLECT SCHOOL ID</h4>
              {action.collect_school_id === true ? (
                <p className="yes">Yes</p>
              ) : (
                <p className="no">No</p>
              )}
            </li>
          </ul>
        </div>
        {campaign.is_open && !isAdmin ? null : (
          <div className="container__row">
            <a
              className="button -secondary"
              href={`/actions/${action.id}/edit`}
            >
              Edit Action
            </a>

            {!this.props.isPermalink ? (
              <button
                className="button delete -tertiary"
                onClick={e => this.props.deleteAction(action.id, e)}
              >
                Delete Action
              </button>
            ) : null}
          </div>
        )}
      </div>
    );
  }
}

Action.propTypes = {
  action: PropTypes.object.isRequired,
  permalink: PropTypes.bool,
};

Action.defaultProps = {
  permalink: false,
};

export default Action;
