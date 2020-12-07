<?php

namespace Zareismail\Maintainable;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Nova\Nova as LaravelNova; 

class MaintainableServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Models\MaintenanceIssue::class => Policies\MaintenanceIssue::class, 
        Models\MaintenanceAction::class => Policies\MaintenanceAction::class, 
        Models\MaintenanceCategory::class => Policies\MaintenanceCategory::class, 
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    { 
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations'); 
        LaravelNova::serving([$this, 'servingNova']);
        $this->registerPolicies();
    } 

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register any Nova services.
     *
     * @return void
     */
    public function servingNova()
    {
        LaravelNova::resources([
            Nova\Issue::class, 
            Nova\Action::class, 
            Nova\Category::class, 
        ]);
    }
}
