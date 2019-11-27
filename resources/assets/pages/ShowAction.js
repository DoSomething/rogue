import gql from 'graphql-tag';
import React, { useState } from 'react';
import { useParams } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import NotFound from './NotFound';
import Action, { ActionFragment } from '../components/Action';
import Shell from '../components/utilities/Shell';
import MetaInformation from '../components/utilities/MetaInformation';

const SHOW_ACTION_QUERY = gql`
  query ShowActionQuery($id: Int!) {
    action(id: $id) {
      ...ActionFragment
    }
  }
  ${ActionFragment}
`;

const ShowAction = () => {
  const { id } = useParams();
  const title = `Action #${id}`;

  const { loading, error, data } = useQuery(SHOW_ACTION_QUERY, {
    variables: { id: Number(id) },
  });

  if (error) {
    return <Shell error={error} />;
  }

  if (loading) {
    return <Shell title={title} loading />;
  }

  if (!data.action) {
    return <NotFound title={title} type="action" />;
  }

  const { campaign, name } = data.action;

  return (
    <Shell title={title} subtitle={name}>
      <Action action={data.action} campaign={{ id: campaign.id }} isPermalink />
      <ul className="form-actions margin-vertical">
        <li>
          <a className="button -tertiary" href={`/campaigns/${campaign.id}`}>
            View all Actions for Campaign {campaign.internalTitle}
          </a>
        </li>
      </ul>
    </Shell>
  );
};

export default ShowAction;
