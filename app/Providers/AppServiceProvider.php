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
            'Resume',
            'History',
            'Banner',
            'Dashboard',
            'AnnouncementResume'
        );

        foreach ($models as $model) {
            $this->app->bind(
                "App\Repositories\\{$model}RepositoryInterface",
                "App\Repositories\\{$model}Repository"
            );
        }

        $this->app->bind("App\Traits\CompaniesExport");
        $this->app->bind("App\Traits\AnnouncmentsExport");
        $this->app->bind("App\Traits\DashboardExport");
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
