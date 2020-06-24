import React from 'react';
import gql from 'graphql-tag';
import { useQuery } from '@apollo/react-hooks';

import EntityLabel from './utilities/EntityLabel';

const GROUP_TYPE_CAMPAIGN_LIST_QUERY = gql`
  query GroupTypeCampaignListQuery($groupTypeId: Int!) {
    paginatedCampaigns(groupTypeId: $groupTypeId) {
      edges {
        node {
          id
          internalTitle
        }
      }
    }
  }
`;

const GroupTypeCampaignList = ({ groupTypeId }) => {
  const { loading, error, data } = useQuery(GROUP_TYPE_CAMPAIGN_LIST_QUERY, {
    variables: { groupTypeId },
  });

  if (loading) {
    return <div className="spinner" />;
  }

  if (error) {
    return <>{JSON.stringify(error)}</>;
  }

  if (!data.paginatedCampaigns || !data.paginatedCampaigns.edges) {
    return <>--</>;
  }

  return (
    <ul>
      {data.paginatedCampaigns.edges.map(item => (
        <li key={item.node.id}>
          <a href={`/campaigns/${item.node.id}`}>
            <EntityLabel name={item.node.internalTitle} id={item.node.id} />
          </a>
        </li>
      ))}
    </ul>
  );
};

export default GroupTypeCampaignList;
