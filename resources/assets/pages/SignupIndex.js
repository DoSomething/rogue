import React from 'react';
import gql from 'graphql-tag';
import queryString from 'query-string';
import { Link } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import Shell from '../components/utilities/Shell';
import SignupsTable from '../components/SignupsTable';
import EntityLabel from '../components/utilities/EntityLabel';

const SignupIndex = () => {
  const title = 'Signups';
  document.title = title;

  const query = queryString.parse(location.search);
  const campaignId = query['campaign_id'];

  return (
    <Shell
      title={title}
      subtitle={campaignId ? `Campaign #${campaignId}` : null}
    >
      <div className="container__block">
        <SignupsTable campaignId={query['campaign_id']} />
      </div>
    </Shell>
  );
};

export default SignupIndex;
