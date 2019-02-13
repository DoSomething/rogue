import React from 'react';
import PropTypes from 'prop-types';
import { map, isEmpty } from 'lodash';

import './campaignidsingle.scss';

import Action from '../Action';
import UploaderModal from '../UploaderModal';
import ModalContainer from '../ModalContainer';
// import CreateActionModal from '../CreateActionModal';

class CampaignIdSingle extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      displayCreateActionModal: false,
    };

    // this.showCreateActionModal = this.showCreateActionModal.bind(this);
    // this.hideCreateActionModal = this.hideCreateActionModal.bind(this);
  }

  // Open the create action modal
  // showCreateActionModal(campaign, event) {
  //   event.preventDefault();

  //   this.setState({
  //     displayCreateActionModal: true,
  //     campaign,
  //   });
  // }

  // Close the create action modal
  // hideCreateActionModal(event) {
  //   if (event) {
  //     event.preventDefault();
  //   }

  //   this.setState({
  //     displayCreateActionModal: false,
  //     campaign: null,
  //   });
  // }

  render() {
    const campaign = this.props.campaign;
    const actions = this.props.actions;

    return (
      <div className="container -padded">
        <div className="container__block -narrow -half">
          <h3>Campaign Information</h3>
        </div>
        <div className="container__block -narrow -half">
          <a
            className="button -secondary"
            href={`/campaigns/${campaign.id}/inbox`}
          >
            Campaign Inbox
          </a>
        </div>
        <div className="container__block -narrow">
          <h4>Internal Campaign Name</h4>
          <p>{campaign.internal_title}</p>

          <h4>Campaign ID</h4>
          <p>{campaign.id}</p>

          <h4>Cause Area</h4>
          <p>{campaign.cause ? campaign.cause : '–'}</p>

          <h4>Proof of Impact</h4>
          {campaign.impact_doc ? (
            <p>
              <a href={campaign.impact_doc} target="_blank">
                {campaign.impact_doc}
              </a>
            </p>
          ) : (
            <p>–</p>
          )}
          <h4>Start Date</h4>
          <p>{new Date(campaign.start_date).toDateString()}</p>

          <h4>End Date</h4>
          <p>
            {campaign.end_date
              ? new Date(campaign.end_date).toDateString()
              : '–'}
          </p>
        </div>
        <div className="container__block -narrow">
          <a className="button" href={`/campaign-ids/${campaign.id}/edit`}>
            Edit this campaign
          </a>
          <p className="footnote">
            Last updated:{' '}
            {new Date(campaign.updated_at).toLocaleString('en-US', {
              timeZone: 'America/New_York',
            })}
            <br />
            Created:{' '}
            {new Date(campaign.created_at).toLocaleString('en-US', {
              timeZone: 'America/New_York',
            })}
          </p>
        </div>

        <div className="container__block -narrow">
          <h3>Campaign Actions</h3>
          <p>
            Each action in your campaign requires a different set of metadata,
            to determine how it will be treated by Rogue. Use this Action ID in
            Contentful to link user submissions in Rogue.
          </p>
          {!isEmpty(actions)
            ? map(actions, (action, key) => {
                return <Action key={key} action={action} />;
              })
            : null}
        </div>

        <div className="container__block -narrow">
          <a
            className="button -secondary"
            href={`${campaign.id}/actions/create`}
          >
            Add Action
          </a>
        </div>
      </div>
    );
  }
}

CampaignIdSingle.propTypes = {
  campaign: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
  actions: PropTypes.array,
};

CampaignIdSingle.defaultProps = {
  actions: null,
};

export default CampaignIdSingle;
