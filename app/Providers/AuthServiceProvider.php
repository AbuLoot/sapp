<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('allow-filemanager', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('allow-filemanager');
        });

        Gate::define('allow-calc', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('allow-calc');
        });

        Gate::define('export', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('export');
        });

        Gate::define('import', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('import');
        });

        Gate::define('joytable', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('joytable');
        });

        // Cashdesk Gates
        Gate::define('opening-cash', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('opening-cash');
        });

        Gate::define('closing-cash', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('closing-cash');
        });

        Gate::define('return-products', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('return-products');
        });

        Gate::define('incoming-cash', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('incoming-cash');
        });

        Gate::define('outgoing-cash', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('outgoing-cash');
        });

        Gate::define('switch-price-mode', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('switch-price-mode');
        });

        Gate::define('list-of-debtors', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('list-of-debtors');
        });

        Gate::define('set-discount', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('set-discount');
        });

        Gate::define('sale-on-credit', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('sale-on-credit');
        });

        // Storage Gates
        Gate::define('add-product', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('add-product');
        });

        Gate::define('edit-product', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('edit-product');
        });

        Gate::define('delete-products', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('delete-products');
        });

        Gate::define('docs', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('docs');
        });

        Gate::define('income', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('income');
        });

        Gate::define('inventory', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('inventory');
        });

        Gate::define('writeoff', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('writeoff');
        });

        Gate::define('storedocs', function(User $user) {
            return $user->roles->first()->permissions->pluck('name')->contains('storedocs');
        });
    }
}
