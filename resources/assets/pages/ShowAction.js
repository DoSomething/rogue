import gql from 'graphql-tag';
import React, { useState } from 'react';
import { useParams } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import NotFound from './NotFound';
import Action from '../components/Action';
import Shell from '../components/utilities/Shell';
import MetaInformation from '../components/utilities/MetaInformation';

const SHOW_ACTION_QUERY = gql`
  query ShowActionQuery($id: Int!) {
    action(id: $id) {
      id
      name
      campaignId
    }
  }
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

  const { campaignId, name } = data.action;

  return (
    <Shell title={name}>
      <Action action={data.action} campaign={{ id: campaignId }} isPermalink />
      <ul className="form-actions margin-vertical">
        <li>
          <a className="button -tertiary" href={`/campaign-ids/${campaignId}`}>
            View all Actions for Campaign {campaignId}
          </a>
        </li>
      </ul>
    </Shell>
  );
};

export default ShowAction;
