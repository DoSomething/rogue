import React from 'react';
import { Router } from '@reach/router';
import { ApolloProvider } from '@apollo/react-hooks';

import { env } from '../helpers';
import graphql from '../graphql';
import UserOverview from './UserOverview';

const Application = () => {
  const endpoint = env('GRAPHQL_URL');

  return (
    <ApolloProvider client={graphql(endpoint)}>
      <Router>
        <UserOverview path="users/:id" />
      </Router>
    </ApolloProvider>
  );
};

export default Application;
