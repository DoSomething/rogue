import React from 'react';
import gql from 'graphql-tag';
import { map } from 'lodash';
import { useParams } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import SignupCard from '../SignupCard';
import Shell from '../utilities/Shell';
import MetaInformation from '../MetaInformation';
import UserInformation, {
  UserInformationFragment,
} from '../Users/UserInformation';

const USER_OVERVIEW_QUERY = gql`
  query UserOverviewQuery($id: String!) {
    user(id: $id) {
      ...UserInformation
      permalink
      source
    }

    signups(userId: $id, orderBy: "created_at,desc") {
      id
      quantity
      whyParticipated

      campaign {
        id
        internalTitle
        startDate
      }

      posts {
        id
        type
        url(w: 200, h: 200)
      }
    }
  }

  ${UserInformationFragment}
`;

const UserOverview = () => {
  const { id } = useParams();

  const title = 'Members';
  const subtitle = 'User profiles & signups...';
  const { loading, error, data } = useQuery(USER_OVERVIEW_QUERY, {
    variables: { id },
  });

  if (loading) {
    return <Shell title={title} subtitle={subtitle} loading />;
  }

  if (error) {
    return <Shell title={title} subtitle={subtitle} error={error} />;
  }

  const { user, signups } = data;

  return (
    <Shell title={title} subtitle={subtitle}>
      <div className="container__block">
        <h2 className="heading -emphasized -padded">
          <span>User Info</span>
        </h2>
      </div>

      <div className="container__block -half">
        <UserInformation user={user}>
          <MetaInformation
            title="Meta"
            details={{
              Source: user.source,
              'Northstar ID': <a href={user.permalink}>{user.id}</a>,
            }}
          />
        </UserInformation>
      </div>

      <div className="container__block">
        <h2 className="heading -emphasized -padded">
          <span>Campaigns</span>
        </h2>
        {map(signups, signup => {
          return <SignupCard key={signup.id} signup={signup} />;
        })}
      </div>
    </Shell>
  );
};

export default UserOverview;
