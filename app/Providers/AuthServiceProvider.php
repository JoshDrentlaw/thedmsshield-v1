<?php

namespace App\Providers;

use App\Policies\CampaignPolicy;
use App\Models\Campaign;
use App\Debug\Debug;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        // 'App\Campaign' => 'App\Policies\CampaignPolicy'
        Campaign::class => CampaignPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('campaign-member', function ($user, $campaign) {
            return $user->id === $campaign->dm->id || in_array($user->id, $campaign->active_player_ids);
        });
    }
}