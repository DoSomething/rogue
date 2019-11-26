import gql from 'graphql-tag';
import React, { useState } from 'react';
import { useParams } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

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

  const { loading, error, data } = useQuery(SHOW_ACTION_QUERY, {
    variables: { id: Number(id) },
  });

  if (error) {
    return <Shell error={error} />;
  }

  if (loading) {
    return <Shell title="Action" loading />;
  }

  const { campaignId, name } = data.action;

  return (
    <Shell
      title={name}
      subtitle={
        <a href={`/campaign-ids/${campaignId}`}>{`Campaign ${campaignId}`}</a>
      }
    >
      <Action action={data.action} campaign={{ id: campaignId }} />
    </Shell>
  );
};

export default ShowAction;
