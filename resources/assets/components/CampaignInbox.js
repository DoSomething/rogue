import React from 'react';
import { get } from 'lodash';
import gql from 'graphql-tag';
import { useParams } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import Empty from './Empty';
import Chrome from './utilities/Chrome';
import { updateQuery } from '../helpers';
import ReviewablePost, { ReviewablePostFragment } from './ReviewablePost';

const CAMPAIGN_INBOX_QUERY = gql`
  query CampaignInboxQuery($id: String!, $intId: Int!, $cursor: String) {
    campaign(id: $intId) {
      internalTitle
    }

    posts: paginatedPosts(
      campaignId: $id
      status: "pending"
      after: $cursor
      first: 10
    ) {
      edges {
        cursor
        node {
          ...ReviewablePost
        }
      }

      pageInfo {
        hasNextPage
        endCursor
      }
    }
  }

  ${ReviewablePostFragment}
`;

const ShowCampaign = () => {
  const { id } = useParams();
  const title = `Campaign Inbox`;

  const { loading, error, data, fetchMore } = useQuery(CAMPAIGN_INBOX_QUERY, {
    variables: { id, intId: Number(id) },
    notifyOnNetworkStatusChange: true,
  });

  if (error) {
    return <Chrome error={error} />;
  }

  const posts = get(data, 'posts.edges', []);
  const subtitle = get(data, 'campaign.internalTitle', 'Loading...');
  const { endCursor, hasNextPage } = get(data, 'posts.pageInfo', {});
  const handleViewMore = () => {
    fetchMore({
      variables: { cursor: endCursor },
      updateQuery,
    });
  };

  if (posts.length == 0) {
    return loading ? (
      <Chrome title={title} subtitle={subtitle} loading />
    ) : (
      <Chrome title={title} subtitle={subtitle}>
        <Empty
          header="There are no new posts!"
          copy="Great job, there are no new posts to review!"
        />
      </Chrome>
    );
  }

  return (
    <Chrome title={title} subtitle={subtitle}>
      {posts.map(edge => (
        <ReviewablePost key={edge.cursor} post={edge.node} />
      ))}
      {loading ? (
        <div className="container__block">
          <div className="spinner margin-horizontal-auto margin-vertical" />
        </div>
      ) : null}
      <div className="form-actions -padded">
        {hasNextPage ? (
          <button
            className="button -tertiary"
            onClick={handleViewMore}
            disabled={loading}
          >
            view more...
          </button>
        ) : (
          <p className="footnote margin-horizontal-auto">...and that's all!</p>
        )}
      </div>
    </Chrome>
  );
};

export default ShowCampaign;
