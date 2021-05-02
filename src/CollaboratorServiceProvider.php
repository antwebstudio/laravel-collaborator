<?php

namespace Slizk\Collaborator;

use Illuminate\Support\ServiceProvider;

class CollaboratorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/collaborator.php', 'collaborator');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
