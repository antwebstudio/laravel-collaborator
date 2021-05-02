<?php

namespace Slizk\Collaborator\Tests;

use Slizk\Collaborator\Tests\Models\CollaboratableActor;
use Slizk\Collaborator\Tests\Models\User;

class ScopeOwnedByTest extends TestCase
{
    public function testScopeOwnedBy()
    {
        foreach ($this->getCollaborateOwnedBySamples() as $sample) {
            $owner = $sample['owner'];
            $collaboratables = $sample['owned'];

            $withs = CollaboratableActor::ownedBy($owner);

            $expectedIds = $collaboratables->pluck('id')->sort();
            $actualIds = $withs->pluck('id')->sort();

            $this->assertEquals($expectedIds, $actualIds);
        }
    }

    protected function getCollaborateOwnedBySamples(): array
    {
        // collaboratables
        $collaboratable1 = CollaboratableActor::create([]);
        $collaboratable2 = CollaboratableActor::create([]);
        $collaboratable3 = CollaboratableActor::create([]);
        $collaboratable4 = CollaboratableActor::create([]);
        $collaboratable5 = CollaboratableActor::create([]);

        // owners
        $owner1 = User::create([]);
        $owner2 = User::create([]);
        $owner3 = User::create([]);

        // mapping
        $collaborateWithSamples = [
            [
                'owner' => $owner1,
                'owned' => collect([
                    $collaboratable1,
                    $collaboratable2,
                    $collaboratable5,
                ]),
            ],
            [
                'owner' => $owner2,
                'owned' => collect([
                    $collaboratable3,
                    $collaboratable5,
                ]),
            ],
            [
                'owner' => $owner3,
                'owned' => collect([
                    $collaboratable1,
                    $collaboratable2,
                    $collaboratable3,
                    $collaboratable4,
                    $collaboratable5,
                ]),
            ],
        ];

        // build collabrations
        foreach ($collaborateWithSamples as $sample) {
            $owner = $sample['owner'];
            $collaboratables = $sample['owned'];
            foreach ($collaboratables as $collabratable) {
                $collabratable->addCollaboratorOwner($owner);
            }
        }

        return $collaborateWithSamples;
    }
}
