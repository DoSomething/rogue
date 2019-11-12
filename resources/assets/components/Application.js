import React from 'react';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';
import { ApolloProvider } from '@apollo/react-hooks';

import { env } from '../helpers';
import graphql from '../graphql';
import ShowPost from './ShowPost';
import UserIndex from './UserIndex';
import UserOverview from './UserOverview';
import CampaignInbox from './CampaignInbox';
import CampaignIndex from './CampaignIndex';

const Application = () => {
  const endpoint = env('GRAPHQL_URL');

  return (
    <ApolloProvider client={graphql(endpoint)}>
      <Router>
        <Switch>
          <Route path="/campaigns" exact>
            <CampaignIndex />
          </Route>
          <Route path="/campaigns/:id/inbox">
            <CampaignInbox />
          </Route>
          <Route path="/users" exact>
            <UserIndex />
          </Route>
          <Route path="/users/:id">
            <UserOverview />
          </Route>
          <Route path="/posts/:id">
            <ShowPost />
          </Route>
        </Switch>
      </Router>
    </ApolloProvider>
  );
};

export default Application;
