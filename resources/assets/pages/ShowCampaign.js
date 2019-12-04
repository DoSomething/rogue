import gql from 'graphql-tag';
import React, { useState } from 'react';
import { useParams, useHistory } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import { STATUSES, TAGS } from '../helpers';
import NotFound from './NotFound';
import Shell from '../components/utilities/Shell';
import Select from '../components/utilities/Select';
import Campaign from '../components/Campaign';
import HelpLink from '../components/utilities/HelpLink';
import ReviewablePostGallery from '../components/ReviewablePostGallery';

const SHOW_CAMPAIGN_QUERY = gql`
  query ShowCampaignQuery($id: Int!) {
    campaign(id: $id) {
      id
      internalTitle
    }
  }
`;

const ShowCampaign = () => {
  const { id, status } = useParams();
  const [tag, setTag] = useState('');
  const history = useHistory();
  const title = `Campaign #${id}`;

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
    return <Shell title={title} loading />;
  }

  if (!data.campaign) {
    return <NotFound title={title} type="campaign" />;
  }

  if (!status) {
    return (
      <Shell title={title} subtitle={data.campaign.internalTitle}>
        <div className="container__row">
          <Campaign id={data.campaign.id} />
        </div>
      </Shell>
    );
  }

  return (
    <Shell title={title} subtitle={data.campaign.internalTitle}>
      <div className="container__row">
        <div className="container__block -third">
          <h4>Filter by status...</h4>
          <Select values={STATUSES} value={status} onChange={setStatus} />
        </div>

        <div className="container__block -third">
          <h4>
            Filter by tag... <HelpLink to="/faq#tags" title="Tag definitions" />
          </h4>
          <Select values={TAGS} value={tag} onChange={setTag} />
        </div>

        <div className="container__block -third form-actions -inline text-right pt-heading">
          <a className="button -tertiary" href={`/campaigns/${id}`}>
            View Campaign &amp; Actions
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
