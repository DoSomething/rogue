import { get } from 'lodash';
import gql from 'graphql-tag';
import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import Empty from './Empty';
import { formatDateTime } from '../helpers';

const USER_REFERRALS_QUERY = gql`
  query UserReferralsQuery($userId: String) {
    posts(referrerUserId: $userId) {
      id
      createdAt
      userId
      type
      status
    }
  }
`;

/**
 * This component handles fetches referrals for a given user, displaying only posts for now.
 *
 * @param String userId
 */
const ReferralsTable = ({ userId }) => {
  const { error, loading, data } = useQuery(USER_REFERRALS_QUERY, {
    variables: { userId },
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

export default ReferralsTable;
