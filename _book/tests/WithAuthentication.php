<?php

namespace Tests;

use Rogue\Models\User;

trait WithAuthentication
{
    /**
     * Create an administrator & log them in to the application.
     *
     * @return $this
     */
    public function actingAsAdmin()
    {
        $user = factory(User::class, 'admin')->create();

        return $this->actingAs($user);
    }

    /**
     * Set the Rogue API key on the request.
     *
     * @return $this
     */
    public function withRogueApiKey()
    {
        $header = $this->transformHeadersToServerVars(['X-DS-Rogue-API-Key' => env('ROGUE_API_KEY')]);

        $this->serverVariables = array_merge($this->serverVariables, $header);

        return $this;
    }
}
