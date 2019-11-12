import React from 'react';
import { get } from 'lodash';
import gql from 'graphql-tag';
import { useParams } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import Empty from './Empty';
import Shell from './utilities/Shell';
import { updateQuery } from '../helpers';
import ReviewablePost, { ReviewablePostFragment } from './ReviewablePost';

const REVIEWABLE_POSTS_QUERY = gql`
  query ReviewablePostsQuery(
    $campaignId: String
    $status: String
    $tags: [String]
    $cursor: String
  ) {
    posts: paginatedPosts(
      campaignId: $campaignId
      status: $status
      tags: $tags
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

const ReviewablePostGallery = ({ campaignId, status }) => {
  const { loading, error, data, fetchMore } = useQuery(REVIEWABLE_POSTS_QUERY, {
    variables: { campaignId, status },
    notifyOnNetworkStatusChange: true,
  });

  if (error) {
    return <Shell error={error} />;
  }

  const posts = get(data, 'posts.edges', []);
  const { endCursor, hasNextPage } = get(data, 'posts.pageInfo', {});

  const handleViewMore = () => {
    fetchMore({
      variables: { cursor: endCursor },
      updateQuery,
    });
  };

  if (posts.length == 0) {
    return loading ? (
      <div className="placeholder flex-center-xy">
        <div className="spinner" />
      </div>
    ) : (
      <div className="margin-vertical">
        <Empty
          header="There are no new posts!"
          copy="Great job, there are no new posts to review!"
        />
      </div>
    );
  }

  return (
    <>
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
    </>
  );
};

export default ReviewablePostGallery;
