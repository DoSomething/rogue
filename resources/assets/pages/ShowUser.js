import React from 'react';
import gql from 'graphql-tag';
import { map } from 'lodash';
import { useParams } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import NotFound from './NotFound';
import Shell from '../components/utilities/Shell';
import SignupGallery from '../components/SignupGallery';
import ReferralsTable from '../components/ReferralsTable';
import MetaInformation from '../components/utilities/MetaInformation';
import UserInformation, {
  UserInformationFragment,
} from '../components/utilities/UserInformation';

const SHOW_USER_QUERY = gql`
  query ShowUserQuery($id: String!) {
    user(id: $id) {
      ...UserInformation
      permalink
      source
    }
  }

  ${UserInformationFragment}
`;

/**
 * @param string selectedTab
 */
const ShowUser = ({ selectedTab }) => {
  const { id } = useParams();

  const title = 'Members';
  const subtitle = 'User profiles & signups...';
  const { loading, error, data } = useQuery(SHOW_USER_QUERY, {
    variables: { id },
  });

  if (loading) {
    return <Shell title={title} subtitle={subtitle} loading />;
  }

  if (error) {
    return <Shell title={title} subtitle={subtitle} error={error} />;
  }

  if (!data.user) {
    return <NotFound title={title} type="user" />;
  }

  const { user } = data;

  return (
    <Shell title={title} subtitle={subtitle}>
      <div className="container__block -half">
        <h2 className="heading -emphasized -padded mb-4">
          <span>User Info</span>
        </h2>
        <UserInformation user={user}>
          <MetaInformation
            details={{
              ID: (
                <span>
                  {user.id} <a href={user.permalink}>(view full profile)</a>
                </span>
              ),
              'Registration Source': user.source,
            }}
          />
        </UserInformation>
      </div>
      <div className="container__block">
        {selectedTab === 'referrals' ? (
          <React.Fragment>
            <h2 className="heading -emphasized -padded">
              <span>Referrals</span>
            </h2>
            <div className="container__block">
              <ReferralsTable userId={user.id} />
            </div>
          </React.Fragment>
        ) : (
          <React.Fragment>
            <h2 className="heading -emphasized -padded">
              <span>Campaigns</span>
            </h2>
            <SignupGallery userId={user.id} />
          </React.Fragment>
        )}
      </div>
    </Shell>
  );
};

export default ShowUser;
