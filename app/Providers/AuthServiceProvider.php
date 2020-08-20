<?php

namespace Rogue\Providers;

use Rogue\Models\Post;
use Rogue\Models\Signup;
use Rogue\Models\Campaign;
use Rogue\Policies\PostPolicy;
use Rogue\Policies\SignupPolicy;
use Rogue\Policies\CampaignPolicy;
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
