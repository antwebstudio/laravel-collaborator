<?php

namespace Slizk\Collaborator\Tests;

use Slizk\Collaborator\CollaboratorServiceProvider;

class TestServiceProvider extends CollaboratorServiceProvider
{
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/collaborator.php', 'collaborator');
        parent::boot();
    }
}
