import gql from 'graphql-tag';
import React, { useState } from 'react';
import { useParams } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import NotFound from './NotFound';
import Empty from '../components/Empty';
import Shell from '../components/utilities/Shell';
import MetaInformation from '../components/utilities/MetaInformation';

const SHOW_GROUP_QUERY = gql`
  query ShowGroupQuery($id: Int!) {
    group(id: $id) {
      createdAt
      groupTypeId
      name
    }
  }
`;

const ShowGroup = () => {
  const { id } = useParams();
  const title = `Group #${id}`;
  document.title = title;

  const { loading, error, data } = useQuery(SHOW_GROUP_QUERY, {
    variables: { id: Number(id) },
  });

  if (error) {
    return <Shell error={error} />;
  }

  if (loading) {
    return <Shell title={title} loading />;
  }

  if (!data.group) return <NotFound title={title} type="group" />;

  const { createdAt, groupTypeId, name } = data.group;

  return (
    <Shell title={title} subtitle={name}>
      <div className="container__row">
        <div className="container__block -half">
          <MetaInformation
            details={{
              Created: createdAt,
            }}
          />
        </div>
        <div className="container__block -half form-actions -inline text-right">
          <div className="container__block -narrow">
            <a className="button -tertiary" href={`/groups/${id}/edit`}>
              Edit Group
            </a>
          </div>
        </div>
      </div>
      <div className="container__row">
        <div className="container__block">
          <h3>Signups</h3>
          <Empty />
        </div>
      </div>
      <ul className="form-actions margin-vertical">
        <li>
          <a className="button -tertiary" href={`/group-types/${groupTypeId}`}>
            View all Groups for Group Type #{groupTypeId}
          </a>
        </li>
      </ul>
    </Shell>
  );
};

export default ShowGroup;
