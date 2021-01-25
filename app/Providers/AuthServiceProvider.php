<?php

namespace App\Providers;

use App\Models\Campaign;
use App\Models\Post;
use App\Models\Signup;
use App\Policies\CampaignPolicy;
use App\Policies\PostPolicy;
use App\Policies\SignupPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Post::class => PostPolicy::class,
        Signup::class => SignupPolicy::class,
        Campaign::class => CampaignPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
