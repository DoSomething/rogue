import { get } from 'lodash';
import gql from 'graphql-tag';
import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import Empty from './Empty';
import { formatDateTime } from '../helpers';

const POSTS_INDEX_QUERY = gql`
  query PostsIndexQuery($referrerUserId: String, $cursor: String) {
    posts: paginatedPosts(
      after: $cursor
      first: 50
      referrerUserId: $referrerUserId
    ) {
      edges {
        cursor
        node {
          id
          createdAt
          userId
          type
          status
        }
      }
      pageInfo {
        endCursor
        hasNextPage
      }
    }
  }
`;

/**
 * This component handles fetching & paginating a list of posts.
 *
 * @param {String} referrerUserId
 */
const PostsTable = ({ referrerUserId }) => {
  const { error, loading, data } = useQuery(POSTS_INDEX_QUERY, {
    variables: { referrerUserId },
    notifyOnNetworkStatusChange: true,
  });

  if (error) {
    return (
      <div className="text-center">
        <p>There was an error. :(</p>
        <code>{JSON.stringify(error)}</code>
      </div>
    );
  }

  if (loading) {
    return (
      <div className="h-content flex-center-xy">
        <div className="spinner" />
      </div>
    );
  }

  const posts = data ? data.posts.edges : [];
  console.log(posts);

  if (posts.length == 0) {
    return (
      <div className="h-content flex-center-xy">
        <Empty copy="No posts found." />
      </div>
    );
  }

  return (
    <>
      <table className="table">
        <thead>
          <tr>
            <td>Created</td>
            <td>User</td>
            <td>Type</td>
            <td>Status</td>
          </tr>
        </thead>
        <tbody>
          {posts.map(({ node, cursor }) => (
            <tr key={node.id}>
              <td>
                <Link to={`/posts/${node.id}`}>
                  {formatDateTime(node.createdAt)}
                </Link>
              </td>
              <td>
                <Link to={`/users/${node.userId}`}>{node.userId}</Link>
              </td>
              <td>{node.type}</td>
              <td>{node.status}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </>
  );
};

export default PostsTable;
