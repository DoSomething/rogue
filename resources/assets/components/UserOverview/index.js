import React from 'react';
import gql from 'graphql-tag';
import { map } from 'lodash';
import { useQuery } from '@apollo/react-hooks';

import Empty from '../Empty';
import SignupCard from '../SignupCard';
import MetaInformation from '../MetaInformation';
import UserInformation, {
  UserInformationFragment,
} from '../Users/UserInformation';

const USER_OVERVIEW_QUERY = gql`
  query UserOverviewQuery($id: String!) {
    user(id: $id) {
      ...UserInformation
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

const UserOverview = ({ id }) => {
  const { loading, error, data } = useQuery(USER_OVERVIEW_QUERY, {
    variables: { id },
  });

  if (loading) return <p>Loading...</p>;
  if (error) return <p>Error :(</p>;

  const { user, signups } = data;

  return (
    <div>
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
              'Northstar ID': user.id,
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
    </div>
  );
};

export default UserOverview;
