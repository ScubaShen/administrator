<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
		 \App\Models\Material::class => \App\Policies\MaterialPolicy::class,
		 \App\Models\Member::class => \App\Policies\MemberPolicy::class,
		 \App\Models\Batch::class => \App\Policies\BatchPolicy::class,
        'App\Model' => 'App\Policies\ModelPolicy',
        \App\Models\Engineering::class => \App\Policies\EngineeringPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
