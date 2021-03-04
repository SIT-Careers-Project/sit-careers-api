<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $models = array(
            'Company',
            'Address',
            'Auth',
            'MOU',
            'Announcement',
            'JobPosition',
            'JobType',
            'User',
            'Role',
            'Permission',
            'RolePermission',
            'Application',
            'History',
            'Banner',
            'Dashboard'
        );

        foreach ($models as $model) {
            $this->app->bind(
                "App\Repositories\\{$model}RepositoryInterface",
                "App\Repositories\\{$model}Repository"
            );
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
