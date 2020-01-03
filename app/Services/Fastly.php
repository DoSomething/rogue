<?php

namespace Rogue\Services;

use Rogue\Models\Post;
use DoSomething\Gateway\Common\RestApiClient;

class Fastly extends RestApiClient
{
    /**
     * The Fastly service for this app.
     */
    protected $service;

    /**
     * Create a new Fastly API client.
     */
    public function __construct()
    {
        $this->service = config('services.fastly.service_id');

        parent::__construct(config('services.fastly.url'), [
            'headers' => [
                'Fastly-Key' => config('services.fastly.key'),
                'Accept'     => 'application/json',
            ],
        ]);
    }

    /**
     * Purge any cached content for the given post.
     */
    public function purge(Post $post): void
    {
        $this->purgeKey('post-' . $post->id);
    }

    /**
     * Purge object from Fastly cache based on give cache key
     *
     * @param $cacheKey String
     */
    protected function purgeKey($cacheKey): void
    {
        if (! $this->service) {
            return;
        }

        $purgeResponse = $this->post('service/'.$this->service.'/purge/'.$cacheKey);
        info('image_cache_purge_successful', ['response' => $purgeResponse]);
    }
}
