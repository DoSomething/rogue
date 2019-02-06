import React from 'react';

import './campaignidsingle.scss';

class CampaignIdSingle extends React.Component {
  render() {
    const campaign = this.props.campaign;
    const actions = this.props.actions;

    return (
      <div className="container -padded">
        <div className="container__block -narrow -half">
          <h2>Campaign Information</h2>
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
          <h3>Internal Campaign Name</h3>
          <p>{campaign.internal_title}</p>

          <h3>Campaign ID</h3>
          <p>{campaign.id}</p>

          <h3>Cause Area</h3>
          <p>{campaign.cause ? campaign.cause : '–'}</p>

          <h3>Proof of Impact</h3>
          {campaign.impact_doc ? (
            <p>
              <a href={campaign.impact_doc} target="_blank">
                {campaign.impact_doc}
              </a>
            </p>
          ) : (
            <p>–</p>
          )}
          <h3>Start Date</h3>
          <p>{new Date(campaign.start_date).toString()}</p>

          <h3>End Date</h3>
          <p>
            {campaign.end_date ? new Date(campaign.end_date).toString() : '–'}
          </p>
        </div>
        <div className="container__block -narrow">
          <a className="button" href={`/campaign-ids/${campaign.id}/edit`}>
            Edit this campaign
          </a>
          <p className="footnote">
            Last updated: {new Date(campaign.updated_at).toString()}
            <br />
            Created: {new Date(campaign.created_at).toString()}
          </p>
        </div>
      </div>
    );
  }
}

export default CampaignIdSingle;
