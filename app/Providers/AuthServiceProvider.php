<?php

namespace App\Providers;

use App\Models\Card;
use App\Models\Listt;
use App\Models\Workboard;
use App\Policies\CardPolicy;
use App\Policies\ListtPolicy;
use App\Policies\WorkboardPolicy;
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
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
         Workboard::class => WorkboardPolicy::class,
         Listt::class => ListtPolicy::class,
         Card::class => CardPolicy::class,
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
