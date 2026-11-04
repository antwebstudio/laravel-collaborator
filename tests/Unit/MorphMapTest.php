<?php

namespace Slizk\Collaborator\Tests\Unit;

use Illuminate\Database\Eloquent\Relations\Relation;
use Slizk\Collaborator\Tests\Models\CollaboratableActor;
use Slizk\Collaborator\Tests\Models\User;
use Slizk\Collaborator\Tests\TestCase;

class MorphMapTest extends TestCase
{
    protected function tearDown(): void
    {
        Relation::morphMap([], false);

        parent::tearDown();
    }

    public function test_scopes_find_models_with_a_morph_map_alias(): void
    {
        Relation::morphMap(['legacy-actor' => CollaboratableActor::class]);

        $actor = CollaboratableActor::create();
        $collaborator = User::create();
        $owner = User::create();
        $actor->addCollaborator($collaborator);
        $actor->addCollaboratorOwner($owner);

        $this->assertDatabaseHas('collaboratables', ['collaboratable_type' => 'legacy-actor']);
        $this->assertTrue(CollaboratableActor::collaboratedWith($collaborator)->exists());
        $this->assertTrue(CollaboratableActor::ownedBy($owner)->exists());
        $this->assertFalse(CollaboratableActor::ownedBy($collaborator)->exists());
    }

    public function test_adding_a_collaborator_twice_keeps_one_row(): void
    {
        $actor = CollaboratableActor::create();
        $user = User::create();

        $actor->addCollaborator($user);
        $actor->addCollaborator($user);

        $this->assertCount(1, $actor->collaborators);
    }
}
