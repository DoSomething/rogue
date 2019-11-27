import React from 'react';
import PropTypes from 'prop-types';
import { map, isEmpty } from 'lodash';
import { format, parse } from 'date-fns';
import { keyBy } from 'lodash';
import RogueClient from '../../utilities/RogueClient';

import './campaignidsingle.scss';

import Action from '../Action';
import PagingButtons from '../PagingButtons';

class Campaign extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      actions: {},
      displayCreateActionModal: false,
    };

    this.api = new RogueClient(window.location.origin, {
      headers: {
        Authorization: `Bearer ${window.AUTH.token}`,
      },
    });

    this.deleteAction = this.deleteAction.bind(this);
    this.getActionsByPaginatedLink = this.getActionsByPaginatedLink.bind(this);
    // this.showCreateActionModal = this.showCreateActionModal.bind(this);
    // this.hideCreateActionModal = this.hideCreateActionModal.bind(this);
  }

  componentDidMount() {
    this.getActions(this.props.campaign.id);
  }

  // Gets campaign's actions.
  getActions(campaignId) {
    this.api
      .get(`api/v3/actions`, {
        filter: {
          campaign_id: campaignId,
        },
      })
      .then(json =>
        this.setState({
          actions: keyBy(json.data, 'id'),
          nextPage: json.meta.pagination.links.next,
          prevPage: json.meta.pagination.links.previous,
        }),
      );
  }

  // Make API call to paginated link to get next/previous batch of posts.
  getActionsByPaginatedLink(url, event) {
    event.preventDefault();

    // Strip the url to get query parameters.
    const splitEndpoint = url.split('/');
    const path = splitEndpoint.slice(-1)[0];
    const queryString = path.split('?')[1];

    this.api.get('api/v3/actions', queryString).then(json =>
      this.setState({
        actions: keyBy(json.data, 'id'),
        nextPage: json.meta.pagination.links.next,
        prevPage: json.meta.pagination.links.previous,
      }),
    );
  }

  // Delete an action.
  deleteAction(actionId, event) {
    event.preventDefault();
    const confirmed = confirm(
      '🚨🔥🚨 Are you sure you want to delete this action? 🚨🔥🚨',
    );

    if (confirmed) {
      // Make API request to Rogue to delete the action.
      const response = this.api.delete(`actions/${actionId}`);

      response.then(result => {
        // Update the state
        this.setState(previousState => {
          const newState = { ...previousState };
          // Remove the deleted action from the state
          delete newState.actions[actionId];

          // Return the new state
          return newState;
        });
      });
    }
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
    const actions = this.state.actions;

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
          <p>
            {campaign.cause_names.length
              ? campaign.cause_names.join(', ')
              : '–'}
          </p>

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
          <p>{format(parse(campaign.start_date), 'MM/D/YYYY')}</p>
          <h4>End Date</h4>
          <p>
            {campaign.end_date
              ? format(parse(campaign.end_date), 'MM/D/YYYY')
              : '–'}
          </p>
        </div>
        <div className="container__block -narrow">
          <a className="button" href={`/campaign-ids/${campaign.id}/edit`}>
            Edit this campaign
          </a>
          <p className="footnote">
            Last updated:{' '}
            {format(parse(campaign.updated_at), 'MM/D/YYYY h:m:s')}
            <br />
            Created: {format(parse(campaign.created_at), 'M/D/YYYY h:m:s')}
          </p>
        </div>

        <div id="actions" className="container__block -narrow">
          <h3>Campaign Actions</h3>
          <p>
            Each action in your campaign requires a different set of metadata,
            to determine how it will be treated by Rogue. Use this Action ID in
            Contentful to link user submissions in Rogue.
          </p>
          {!isEmpty(actions)
            ? map(actions, (action, key) => {
                return (
                  <Action
                    key={key}
                    action={action}
                    deleteAction={this.deleteAction}
                  />
                );
              })
            : null}
        </div>
        <div className="container__block -narrow">
          <PagingButtons
            onPaginate={this.getActionsByPaginatedLink}
            prev={this.state.prevPage}
            next={this.state.nextPage}
          />
        </div>
        <div className="container__block -narrow">
          <a
            className="button -secondary"
            href={`/campaigns/${campaign.id}/actions/create`}
          >
            Add Action
          </a>
        </div>
      </div>
    );
  }
}

Campaign.propTypes = {
  campaign: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
  actions: PropTypes.array,
};

Campaign.defaultProps = {
  actions: null,
};

export default CampaignIdSingle;
