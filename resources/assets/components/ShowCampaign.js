import gql from 'graphql-tag';
import React, { useState } from 'react';
import { useParams, useHistory } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import Shell from './utilities/Shell';
import Select from './utilities/Select';
import { STATUSES, TAGS } from '../helpers';
import ReviewablePostGallery from './ReviewablePostGallery';

const SHOW_CAMPAIGN_QUERY = gql`
  query ShowCampaignQuery($id: Int!) {
    campaign(id: $id) {
      internalTitle
    }
  }
`;

const ShowCampaign = () => {
  const { id, status } = useParams();
  const [tag, setTag] = useState('');
  const history = useHistory();

  const setStatus = value => {
    history.replace(`/campaigns/${id}/${value}`);
  };

  const { loading, error, data } = useQuery(SHOW_CAMPAIGN_QUERY, {
    variables: { id: Number(id) },
  });

  if (error) {
    return <Shell error={error} />;
  }

  if (loading) {
    return <Shell title="Campaign" loading />;
  }

  return (
    <Shell title="Campaign" subtitle={data.campaign.internalTitle}>
      <div className="container__row">
        <div className="container__block -third">
          <h4>Filter by status...</h4>
          <Select values={STATUSES} value={status} onChange={setStatus} />
        </div>

        <div className="container__block -third">
          <h4>Filter by tag...</h4>
          <Select values={TAGS} value={tag} onChange={setTag} />
        </div>

        <div className="container__block -third form-actions -inline text-right pt-heading">
          <a className="button -tertiary" href={`/campaign-ids/${id}`}>
            Edit Campaign &amp; Actions
          </a>
        </div>
      </div>
      <ReviewablePostGallery
        campaignId={id}
        status={status || 'pending'}
        tags={tag !== '' ? [tag] : null}
      />
    </Shell>
  );
};

export default ShowCampaign;
