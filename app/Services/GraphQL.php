<?php

namespace Rogue\Services;

use Illuminate\Support\Facades\Log;
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
     * Run a GraphQL query using the client
     * and parse the data result into a convenient format.
     *
     * @param  $query     String
     * @param  $variables Array
     * @param  $queryKey  String
     * @return Array|null
     */
    public function query($query, $variables, $queryKey)
    {
        // Use try/catch to avoid any GraphQL related errors breaking the application.
        try {
            $response = $this->client->query($query, $variables);
        } catch (\Exception $exception) {
            Log::error(
                'GraphQL request failed. Variables: '.json_encode($variables).' Exception: '.$exception->getMessage()
            );

            return null;
        }

        return $response ? array_get($response->getData(), $queryKey) : null;
    }
    }
}
