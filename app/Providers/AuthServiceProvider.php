<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Preference\Preference;
use App\Policies\PreferencePolicy;


class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Preference::class => PreferencePolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
