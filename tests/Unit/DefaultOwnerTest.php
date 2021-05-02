<?php

namespace Slizk\Collaborator\Tests;

use Illuminate\Support\Facades\DB;
use Slizk\Collaborator\Tests\Models\CollaboratableActor;
use Slizk\Collaborator\Tests\Models\User;

class DefaultOwnerTest extends TestCase
{
    public function testCreatedByAsDefaultOwner()
    {
        $collaboratable1 = CollaboratableActor::create([]);
        $this->assertNull($collaboratable1->owner);

        $owner = User::create([]);
        $collaboratable2 = CollaboratableActor::create(['created_by' => $owner->id]);
        $this->assertTrue($owner->is($collaboratable2->owner));
    }

    public function testOwnerConflict()
    {
        $owner1 = User::create([]);
        $owner2 = User::create([]);

        $collaboratable = CollaboratableActor::create(['created_by' => $owner1->id]);
        $this->assertEquals(collect([$owner1->id]), $collaboratable->owners->pluck('id'));

        $collaboratable->addCollaboratorOwner($owner1);
        $this->assertEquals(collect([$owner1->id]), $collaboratable->owners->pluck('id'));

        $collaboratable->addCollaboratorOwner($owner2);
        $this->assertEquals(collect([$owner1->id, $owner2->id]), $collaboratable->owners->pluck('id'));
    }

    public function testConfig()
    {
        $owner = User::create([]);
        $collaboratable = CollaboratableActor::create(['created_by' => $owner->id]);

        // turn off default owner
        config(['collaborator.owner.created_by_as_default' => false]);
        $this->assertNull($collaboratable->owner);

        // turn on default owner
        config(['collaborator.owner.created_by_as_default' => true]);
        $this->assertTrue($owner->is($collaboratable->owner));
    }
}
