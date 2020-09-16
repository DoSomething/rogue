<?php

namespace Rogue\Services;

use Softonic\GraphQL\ClientBuilder;

class GraphQL
{
    /**
     * Build a new GraphQL client.
     */
    public function __construct()
    {
        $headers = [
            // Identify this client for Apollo GraphQL metrics (https://bit.ly/35Vf3F1).
            'apollographql-client-name' => 'rogue',
        ];

        // If we have an authorization token (machine clients), assign the authorization header for
        // our GraphQL client so that we can request gated fields.
        if (token()->exists()) {
            $headers = array_merge($headers, [
                'authorization' => 'Bearer ' . token()->jwt(),
            ]);
        }

        $this->client = ClientBuilder::build(config('services.graphql.url'), [
            'headers' => $headers,
        ]);
    }

    /**
     * Run a GraphQL query using the client and return the data result.
     *
     * @param  $query     String
     * @param  $variables Array
     * @return array
     */
    public function query($query, $variables)
    {
        $response = $this->client->query($query, $variables);

        return $response ? $response->getData() : [];
    }

    /**
     * Query for a CampaignWebsite by campaignId field.
     *
     * NOTE: We prefer to bundle queries with their associated usage logic.
     * We've opted to utilize this helper method as an exception to avoid duplication.
     * (See thread https://git.io/fjoqd).
     *
     * @param  $campaignId String
     * @return array
     */
    public function getCampaignWebsiteByCampaignId($campaignId)
    {
        $query = '
        query GetCampaignWebsiteByCampaignId($campaignId: String!) {
          campaignWebsiteByCampaignId(campaignId: $campaignId) {
            title
            slug
          }
        }';

        $variables = [
            'campaignId' => $campaignId,
        ];

        return $this->query($query, $variables)['campaignWebsiteByCampaignId'];
    }

    /**
     * Query for a School by ID.
     *
     * @param  $schoolId String
     * @return array
     */
    public function getSchoolById($schoolId)
    {
        $query = '
        query GetSchoolById($schoolId: String!) {
          school(id: $schoolId) {
            name
          }
        }';

        $variables = [
            'schoolId' => $schoolId,
        ];

        return $this->query($query, $variables)['school'];
    }

    /**
     * Query for a User by ID.
     *
     * @param  $userId String
     * @return array
     */
    public function getUserById($userId)
    {
        $query = '
        query GetUserById($userId: String!) {
          user(id: $userId) {
            displayName
          }
        }';

        $variables = [
            'userId' => $userId,
        ];

        return $this->query($query, $variables)['user'];
    }
}
