<?php

namespace Slizk\Collaborator\Tests;

use Slizk\Collaborator\Tests\Models\CollaboratableActor;
use Slizk\Collaborator\Tests\Models\User;

class OwnerTest extends TestCase
{
    public function testAddAndRemove()
    {
        $collaboratable = CollaboratableActor::create([]);

        $owner1 = User::create([]);
        $owner2 = User::create([]);
        $owner3 = User::create([]);
        $user = User::create([]);

        $this->assertFalse($collaboratable->owners->contains($owner1));
        $this->assertFalse($collaboratable->owners->contains($owner2));
        $this->assertFalse($collaboratable->owners->contains($owner3));

        $collaboratable->addCollaboratorOwner($owner1);
        $this->assertTrue($collaboratable->owners->contains($owner1));

        $collaboratable->addCollaboratorOwner($owner2);
        $this->assertTrue($collaboratable->owners->contains($owner2));

        $collaboratable->addCollaboratorOwner($owner3);
        $this->assertTrue($collaboratable->owners->contains($owner3));

        // try remove not owner
        $collaboratable->removeCollaboratorOwner($user);
        $this->assertTrue($collaboratable->owners->contains($owner1));
        $this->assertTrue($collaboratable->owners->contains($owner2));
        $this->assertTrue($collaboratable->owners->contains($owner3));
        $this->assertFalse($collaboratable->owners->contains($user));

        $collaboratable->removeCollaboratorOwner($owner1);
        $this->assertFalse($collaboratable->owners->contains($owner1));

        $collaboratable->removeCollaboratorOwner($owner2);
        $this->assertFalse($collaboratable->owners->contains($owner2));

        $collaboratable->removeCollaboratorOwner($owner3);
        $this->assertFalse($collaboratable->owners->contains($owner3));
    }

    public function testIsOwnedBy()
    {
        $collaboratable = CollaboratableActor::create([]);

        $owned = User::create([]);
        $notOwned = User::create([]);

        $collaboratable->addCollaboratorOwner($owned);

        $this->assertTrue($collaboratable->isOwnedBy($owned));
        $this->assertFalse($collaboratable->isOwnedBy($notOwned));
    }
}
