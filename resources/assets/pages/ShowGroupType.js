import gql from 'graphql-tag';
import React, { useState } from 'react';
import { useParams } from 'react-router-dom';
import { useQuery } from '@apollo/react-hooks';

import NotFound from './NotFound';
import Empty from '../components/Empty';
import Shell from '../components/utilities/Shell';
import MetaInformation from '../components/utilities/MetaInformation';

const SHOW_GROUP_TYPE_QUERY = gql`
  query ShowGroupTypeQuery($id: Int!) {
    groupType(id: $id) {
      createdAt
      name
    }
  }
`;

const ShowGroupType = () => {
  const { id } = useParams();
  const title = `Group Type #${id}`;
  document.title = title;

  const { loading, error, data } = useQuery(SHOW_GROUP_TYPE_QUERY, {
    variables: { id: Number(id) },
  });

  if (error) {
    return <Shell error={error} />;
  }

  if (loading) {
    return <Shell title={title} loading />;
  }

  if (!data.groupType) return <NotFound title={title} type="group type" />;

  const { createdAt, name } = data.groupType;

  return (
    <Shell title={title} subtitle={name}>
      <div className="container__block -half">
        <MetaInformation
          details={{
            Created: createdAt,
          }}
        />
      </div>
      <div className="container__block -half form-actions -inline text-right">
        <a
          className="button -tertiary"
          href={`/group-types/${id}/groups/create`}
        >
          New Group
        </a>
      </div>
      <div className="container__row">
        <div className="container__block">
          <h3>Groups</h3>
          <Empty />
        </div>
      </div>
      <ul className="form-actions margin-vertical">
        <li>
          <a className="button -tertiary" href={`/group-types`}>
            View all Group Types
          </a>
        </li>
      </ul>
    </Shell>
  );
};

export default ShowGroupType;
