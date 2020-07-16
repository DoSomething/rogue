import React from 'react';
import gql from 'graphql-tag';
import { Link } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import Shell from '../components/utilities/Shell';
import SignupsTable from '../components/SignupsTable';
import EntityLabel from '../components/utilities/EntityLabel';

const SignupIndex = () => {
  const title = 'Signups';
  document.title = title;

  return (
    <Shell title={title}>
      <div className="container__block">
        <SignupsTable />
      </div>
    </Shell>
  );
};

export default SignupIndex;
