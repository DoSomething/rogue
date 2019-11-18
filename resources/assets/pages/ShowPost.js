import React from 'react';
import gql from 'graphql-tag';
import { useParams } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import Shell from '../components/utilities/Shell';
import ReviewablePost, {
  ReviewablePostFragment,
} from '../components/ReviewablePost';

const SHOW_POST_QUERY = gql`
  query ShowPostQuery($id: Int!) {
    post(id: $id) {
      ...ReviewablePost

      campaign {
        id
        internalTitle
      }

      user {
        displayName
      }
    }
  }

  ${ReviewablePostFragment}
`;

const ShowPost = () => {
  const { id } = useParams();
  const title = `Post #${id}`;

  const { loading, error, data } = useQuery(SHOW_POST_QUERY, {
    variables: { id: Number(id) },
  });

  if (loading) {
    return <Shell title={title} loading />;
  }

  if (error) {
    return <Shell error={error} />;
  }

  if (!data.post)
    return (
      <Shell title={title} subtitle="Not found!">
        <div className="container__block">
          We couldn't find that post. Maybe it was deleted?
        </div>
      </Shell>
    );

  const { post } = data;
  const subtitle = `${post.user.displayName} / ${post.campaign.internalTitle}`;

  return (
    <Shell title={title} subtitle={subtitle}>
      <ReviewablePost post={post} />
      <ul className="form-actions margin-vertical">
        <li>
          <a
            className="button -tertiary"
            href={`/campaigns/${post.campaign.id}`}
          >
            more from "{post.campaign.internalTitle}"
          </a>
        </li>
      </ul>
    </Shell>
  );
};

export default ShowPost;