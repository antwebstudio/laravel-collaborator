<?php

namespace Slizk\Collaborator\Tests\User;

use Slizk\Collaborator\Tests\Models\CollaboratableActor;
use Slizk\Collaborator\Tests\Models\User;
use Slizk\Collaborator\Tests\TestCase;

class CollaboratorsAddRemoveTest extends TestCase
{
    public function testAddCollaborator()
    {
        $collaboratable = CollaboratableActor::create([]);

        $user1 = User::create([]);
        $user2 = User::create([]);

        $this->assertDatabaseCount('collaboratables', 0);
        $this->assertEquals(0, $collaboratable->collaborators()->count());

        // try to add user 1
        $collaboratable->addCollaborator($user1);
        $this->assertDatabaseHas('collaboratables', [
            'collaborator_type' => get_class($user1),
            'collaborator_id' => $user1->id,
            'collaboratable_type' => get_class($collaboratable),
            'collaboratable_id' => $collaboratable->id,
        ]);
        $this->assertEquals(1, $collaboratable->collaborators()->count());
        $this->assertTrue($collaboratable->collaborators->contains($user1));

        // try to add user 2
        $collaboratable->addCollaborator($user2);
        $this->assertDatabaseHas('collaboratables', [
            'collaborator_type' => get_class($user2),
            'collaborator_id' => $user2->id,
            'collaboratable_type' => get_class($collaboratable),
            'collaboratable_id' => $collaboratable->id,
        ]);
        $this->assertEquals(2, $collaboratable->collaborators()->count());
        $this->assertTrue($collaboratable->collaborators->contains($user2));
    }

    public function testAddCollaborators()
    {
        $collaboratable = CollaboratableActor::create([]);

        $user1 = User::create([]);
        $user2 = User::create([]);

        $this->assertDatabaseCount('collaboratables', 0);
        $this->assertEquals(0, $collaboratable->collaborators()->count());

        $collaboratable->addCollaborators([$user1, $user2]);
        $this->assertDatabaseHas('collaboratables', [
            'collaborator_type' => get_class($user1),
            'collaborator_id' => $user1->id,
            'collaboratable_type' => get_class($collaboratable),
            'collaboratable_id' => $collaboratable->id,
        ]);
        $this->assertDatabaseHas('collaboratables', [
            'collaborator_type' => get_class($user2),
            'collaborator_id' => $user2->id,
            'collaboratable_type' => get_class($collaboratable),
            'collaboratable_id' => $collaboratable->id,
        ]);
        $this->assertTrue($collaboratable->collaborators->contains($user1));
        $this->assertTrue($collaboratable->collaborators->contains($user2));
    }

    public function testRemoveCollaborator()
    {
        $collaboratable = CollaboratableActor::create([]);

        $user1 = User::create([]);
        $user2 = User::create([]);

        $collaboratable->addCollaborator($user1);
        $collaboratable->addCollaborator($user2);

        $this->assertDatabaseCount('collaboratables', 2);
        $this->assertEquals(2, $collaboratable->collaborators()->count());
        $this->assertTrue($collaboratable->collaborators->contains($user1));
        $this->assertTrue($collaboratable->collaborators->contains($user2));

        $collaboratable->removeCollaborator($user1);
        $this->assertDatabaseMissing('collaboratables', [
            'collaborator_type' => get_class($user1),
            'collaborator_id' => $user1->id,
            'collaboratable_type' => get_class($collaboratable),
            'collaboratable_id' => $collaboratable->id,
        ]);
        $this->assertFalse($collaboratable->collaborators->contains($user1));

        $collaboratable->removeCollaborator($user2);
        $this->assertDatabaseMissing('collaboratables', [
            'collaborator_type' => get_class($user2),
            'collaborator_id' => $user2->id,
            'collaboratable_type' => get_class($collaboratable),
            'collaboratable_id' => $collaboratable->id,
        ]);
        $this->assertFalse($collaboratable->collaborators->contains($user2));
    }

    public function testRemoveCollaborators()
    {
        $collaboratable = CollaboratableActor::create([]);

        $user1 = User::create([]);
        $user2 = User::create([]);

        $collaboratable->addCollaborator($user1);
        $collaboratable->addCollaborator($user2);

        $this->assertDatabaseCount('collaboratables', 2);
        $this->assertEquals(2, $collaboratable->collaborators()->count());
        $this->assertTrue($collaboratable->collaborators->contains($user1));
        $this->assertTrue($collaboratable->collaborators->contains($user2));

        $collaboratable->removeCollaborators([$user1, $user2]);
        $this->assertDatabaseMissing('collaboratables', [
            'collaborator_type' => get_class($user1),
            'collaborator_id' => $user1->id,
            'collaboratable_type' => get_class($collaboratable),
            'collaboratable_id' => $collaboratable->id,
        ]);
        $this->assertDatabaseMissing('collaboratables', [
            'collaborator_type' => get_class($user2),
            'collaborator_id' => $user2->id,
            'collaboratable_type' => get_class($collaboratable),
            'collaboratable_id' => $collaboratable->id,
        ]);
        $this->assertFalse($collaboratable->collaborators->contains($user1));
        $this->assertFalse($collaboratable->collaborators->contains($user2));
    }
}
