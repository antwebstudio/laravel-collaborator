<?php

namespace Slizk\Collaborator\Tests;

use Slizk\Collaborator\Tests\Models\CollaboratableActor;
use Slizk\Collaborator\Tests\Models\User;

class OwnerPivotDuplicationTest extends TestCase
{
    public function testPivotDuplication()
    {
        $collaboratable1 = CollaboratableActor::create([]);
        $collaboratable2 = CollaboratableActor::create([]);

        $user1 = User::create([]);
        $user2 = User::create([]);
        $user3 = User::create([]);

        // make sure is empty
        $this->assertEquals(0, $collaboratable1->collaborators()->count());
        $this->assertDatabaseCount('collaboratables', 0);

        // add user1 as collaborator
        $collaboratable1->addCollaborator($user1);
        $this->assertDatabaseHas('collaboratables', [
            'collaborator_type' => get_class($user1),
            'collaborator_id' => $user1->id,
            'collaboratable_type' => get_class($collaboratable1),
            'collaboratable_id' => $collaboratable1->id,
            'is_owner' => false,
        ]);
        $this->assertEquals(1, $collaboratable1->collaborators()->count());
        $this->assertDatabaseCount('collaboratables', 1);

        // add user2 as collaborator
        $collaboratable1->addCollaborator($user2);
        $this->assertDatabaseHas('collaboratables', [
            'collaborator_type' => get_class($user2),
            'collaborator_id' => $user2->id,
            'collaboratable_type' => get_class($collaboratable1),
            'collaboratable_id' => $collaboratable1->id,
            'is_owner' => false,
        ]);
        $this->assertEquals(2, $collaboratable1->collaborators()->count());
        $this->assertDatabaseCount('collaboratables', 2);

        // make existing user1 to owner
        $collaboratable1->addCollaboratorOwner($user1);
        $this->assertDatabaseHas('collaboratables', [
            'collaborator_type' => get_class($user1),
            'collaborator_id' => $user1->id,
            'collaboratable_type' => get_class($collaboratable1),
            'collaboratable_id' => $collaboratable1->id,
            'is_owner' => true,
        ]);
        $this->assertDatabaseHas('collaboratables', [
            'collaborator_type' => get_class($user2),
            'collaborator_id' => $user2->id,
            'collaboratable_type' => get_class($collaboratable1),
            'collaboratable_id' => $collaboratable1->id,
            'is_owner' => false,
        ]);
        $this->assertEquals(2, $collaboratable1->collaborators()->count());
        $this->assertDatabaseCount('collaboratables', 2);

        // direct add user3 as owner
        $collaboratable1->addCollaboratorOwner($user3);
        $this->assertDatabaseHas('collaboratables', [
            'collaborator_type' => get_class($user1),
            'collaborator_id' => $user1->id,
            'collaboratable_type' => get_class($collaboratable1),
            'collaboratable_id' => $collaboratable1->id,
            'is_owner' => true,
        ]);
        $this->assertDatabaseHas('collaboratables', [
            'collaborator_type' => get_class($user2),
            'collaborator_id' => $user2->id,
            'collaboratable_type' => get_class($collaboratable1),
            'collaboratable_id' => $collaboratable1->id,
            'is_owner' => false,
        ]);
        $this->assertDatabaseHas('collaboratables', [
            'collaborator_type' => get_class($user3),
            'collaborator_id' => $user3->id,
            'collaboratable_type' => get_class($collaboratable1),
            'collaboratable_id' => $collaboratable1->id,
            'is_owner' => true,
        ]);
        $this->assertEquals(3, $collaboratable1->collaborators()->count());
        $this->assertDatabaseCount('collaboratables', 3);

        // another collaboratable
        $collaboratable2->addCollaboratorOwner($user1);
        $this->assertDatabaseHas('collaboratables', [
            'collaborator_type' => get_class($user1),
            'collaborator_id' => $user1->id,
            'collaboratable_type' => get_class($collaboratable1),
            'collaboratable_id' => $collaboratable1->id,
            'is_owner' => true,
        ]);
        $this->assertDatabaseHas('collaboratables', [
            'collaborator_type' => get_class($user2),
            'collaborator_id' => $user2->id,
            'collaboratable_type' => get_class($collaboratable1),
            'collaboratable_id' => $collaboratable1->id,
            'is_owner' => false,
        ]);
        $this->assertDatabaseHas('collaboratables', [
            'collaborator_type' => get_class($user3),
            'collaborator_id' => $user3->id,
            'collaboratable_type' => get_class($collaboratable1),
            'collaboratable_id' => $collaboratable1->id,
            'is_owner' => true,
        ]);
        $this->assertDatabaseHas('collaboratables', [
            'collaborator_type' => get_class($user1),
            'collaborator_id' => $user1->id,
            'collaboratable_type' => get_class($collaboratable2),
            'collaboratable_id' => $collaboratable2->id,
            'is_owner' => true,
        ]);
        $this->assertEquals(1, $collaboratable2->collaborators()->count());
        $this->assertDatabaseCount('collaboratables', 4);
    }
}
