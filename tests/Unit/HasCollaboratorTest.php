<?php

namespace Slizk\Collaborator\Tests;

use Slizk\Collaborator\Tests\Models\CollaboratableActor;
use Slizk\Collaborator\Tests\Models\User;

class HasCollaboratorTest extends TestCase
{
    public function testHasCollaborator()
    {
        $collaboratable1 = CollaboratableActor::create([]);
        $collaboratable2 = CollaboratableActor::create([]);

        $user1 = User::create([]);
        $user2 = User::create([]);
        $user3 = User::create([]);

        $collaboratable1->addCollaborator($user1);
        $collaboratable1->addCollaborator($user2);

        $collaboratable2->addCollaborator($user3);

        $this->assertTrue($collaboratable1->hasCollaborator($user1));
        $this->assertTrue($collaboratable1->hasCollaborator($user2));
        $this->assertFalse($collaboratable1->hasCollaborator($user3));

        $this->assertFalse($collaboratable2->hasCollaborator($user1));
        $this->assertFalse($collaboratable2->hasCollaborator($user2));
        $this->assertTrue($collaboratable2->hasCollaborator($user3));
    }
}
