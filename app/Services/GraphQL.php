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
        $this->client = ClientBuilder::build(config('services.graphql.url'));
    }

    /**
     * Run a GraphQL query using the client.
     *
     * @param  $query     String
     * @param  $variables Array
     * @return \Softonic\GraphQL\Response
     */
    public function query($query, $variables)
    {
        return $this->client->query($query, $variables);
    }
}
