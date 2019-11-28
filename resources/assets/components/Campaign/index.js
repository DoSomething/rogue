import gql from 'graphql-tag';
import { map, isEmpty } from 'lodash';
import React, { useState } from 'react';
import { format, parse } from 'date-fns';
import { useQuery } from '@apollo/react-hooks';

import Action, { ActionFragment } from '../Action';
import Shell from '../utilities/Shell';
import TextBlock from '../utilities/TextBlock';

import './campaign.scss';

const SHOW_CAMPAIGN_ACTIONS_QUERY = gql`
  query ShowCampaignActionsQuery($id: Int!, $idString: String!) {
    campaign(id: $id) {
      actions {
        ...ActionFragment
      }
      causes {
        id
        name
      }
      createdAt
      endDate
      id
      impactDoc
      internalTitle
      startDate
      updatedAt
    }
    campaignWebsiteByCampaignId(campaignId: $idString) {
      slug
    }
  }
  ${ActionFragment}
`;

const Campaign = ({ id }) => {
  const { loading, error, data } = useQuery(SHOW_CAMPAIGN_ACTIONS_QUERY, {
    variables: { id, idString: `${id}` },
  });

  if (error) {
    return <TextBlock title="Error" content={JSON.stringify(error)} />;
  }

  if (loading) {
    return <TextBlock title="Loading actions..." />;
  }

  const { campaign } = data;

  return (
    <div className="container -padded">
      <div className="container__block -narrow -half">
        <h3>Campaign Information</h3>
      </div>
      <div className="container__block -narrow -half">
        <a
          className="button -secondary"
          href={`/campaigns/${campaign.id}/pending`}
        >
          Campaign Inbox
        </a>
      </div>
      <div className="container__block -narrow">
        <h4>Internal Campaign Name</h4>
        <p>{campaign.internalTitle}</p>

        <h4>Campaign ID</h4>
        <p>{campaign.id}</p>

        <h4>Cause Area</h4>
        <p>
          {campaign.causes.length
            ? campaign.causes.map(cause => cause.name).join(', ')
            : '–'}
        </p>

        <h4>Proof of Impact</h4>
        {campaign.impactDoc ? (
          <p>
            <a href={campaign.impactDoc} target="_blank">
              {campaign.impactDoc}
            </a>
          </p>
        ) : (
          <p>–</p>
        )}
        <h4>Start Date</h4>
        <p>{format(parse(campaign.startDate), 'MM/D/YYYY')}</p>
        <h4>End Date</h4>
        <p>
          {campaign.end_date
            ? format(parse(campaign.endDate), 'MM/D/YYYY')
            : '–'}
        </p>
      </div>
      <div className="container__block -narrow">
        <a className="button" href={`/campaigns/${campaign.id}/edit`}>
          Edit this campaign
        </a>
        <p className="footnote">
          Last updated: {campaign.updatedAt}
          <br />
          Created: {campaign.createdAt}
        </p>
      </div>
      <div id="actions" className="container__block -narrow">
        <h3>Campaign Actions</h3>
        <p>
          Each action in your campaign requires a different set of metadata, to
          determine how it will be treated by Rogue. Use this Action ID in
          Contentful to link user submissions in Rogue.
        </p>
        {!isEmpty(campaign.actions)
          ? map(campaign.actions, (action, key) => {
              return <Action key={key} action={action} />;
            })
          : null}
        <div className="container__block -narrow">
          <a
            className="button -secondary"
            href={`/campaigns/${campaign.id}/actions/create`}
          >
            Add Action
          </a>
        </div>
      </div>
    </div>
  );
};

export default Campaign;
