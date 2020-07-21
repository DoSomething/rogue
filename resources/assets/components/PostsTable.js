import { get } from 'lodash';
import gql from 'graphql-tag';
import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import Empty from './Empty';
import { formatDateTime } from '../helpers';

const POSTS_INDEX_QUERY = gql`
  query PostsIndexQuery($referrerUserId: String) {
    posts(referrerUserId: $referrerUserId) {
      id
      createdAt
      userId
      type
      status
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

  const { posts } = data;

  if (posts.length == 0) {
    return (
      <div className="h-content flex-center-xy">
        <Empty copy="No referrals found for this user." />
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
          {data.posts.map(post => (
            <tr key={post.id}>
              <td>
                <Link to={`/posts/${post.id}`}>
                  {formatDateTime(post.createdAt)}
                </Link>
              </td>
              <td>
                <Link to={`/users/${post.userId}`}>{post.userId}</Link>
              </td>
              <td>{post.type}</td>
              <td>{post.status}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </>
  );
};

export default PostsTable;
