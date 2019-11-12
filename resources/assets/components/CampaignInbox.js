import React from 'react';
import { get } from 'lodash';
import gql from 'graphql-tag';
import { useParams } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import Shell from './utilities/Shell';
import ReviewablePostGallery from './ReviewablePostGallery';

const CAMPAIGN_INBOX_QUERY = gql`
  query CampaignInboxQuery($id: Int!) {
    campaign(id: $id) {
      internalTitle
    }
  }
`;

const CampaignInbox = () => {
  const { id } = useParams();
  const title = `Campaign Inbox`;

  const { loading, error, data } = useQuery(CAMPAIGN_INBOX_QUERY, {
    variables: { id: Number(id) },
  });

  if (error) {
    return <Shell error={error} />;
  }

  if (loading) {
    return <Shell title={title} subtitle="Loading..." loading />;
  }

  return (
    <Shell title={title} subtitle={data.campaign.internalTitle}>
      <ReviewablePostGallery campaignId={id} status="pending" />
    </Shell>
  );
};

export default CampaignInbox;
