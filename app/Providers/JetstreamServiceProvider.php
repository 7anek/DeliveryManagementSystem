<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);

        Jetstream::role('admin', 'Administrator', [
            'order:edit',
            'order:create',
            'order:view',
            'user:edit',
        ])->description('Administrator has full permissions.');
    
        Jetstream::role('manager', 'Manager', [
            'order:edit',
        ])->description('Manager can manage orders.');
    
        Jetstream::role('client', 'Client', [
            'order:create',
            'order:view',
        ])->description('Client can create and view their own orders.');
    }
}
