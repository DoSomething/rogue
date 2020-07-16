import React from 'react';
import gql from 'graphql-tag';
import { useQuery } from '@apollo/react-hooks';
import { Link, useParams } from 'react-router-dom';

import Shell from '../components/utilities/Shell';
import SignupsTable from '../components/SignupsTable';
import EntityLabel from '../components/utilities/EntityLabel';

const SignupIndex = () => {
  const title = 'Signups';
  document.title = title;
  const { id } = useParams();

  return (
    <Shell
      title={id ? `Campaign #${id}` : 'Signups'}
      subtitle={id ? 'Signups' : null}
    >
      <div className="container__block">
        <SignupsTable campaignId={id} />
      </div>
    </Shell>
  );
};

export default SignupIndex;
