<?php

namespace Slizk\Collaborator\Tests;

use Slizk\Collaborator\Tests\Models\CollaboratableActor;
use Slizk\Collaborator\Tests\Models\User;

class ScopeCollaboratedWithTest extends TestCase
{
    public function testScopeCollaboratedWith()
    {
        foreach ($this->getCollaborateWithSamples() as $sample) {
            $collaborator = $sample['collaborator'];
            $collaboratables = $sample['collaborateWiths'];

            $withs = CollaboratableActor::collaboratedWith($collaborator);

            $expectedIds = $collaboratables->pluck('id')->sort();
            $actualIds = $withs->pluck('id')->sort();

            $this->assertEquals($expectedIds, $actualIds);
        }
    }

    protected function getCollaborateWithSamples(): array
    {
        // collaboratables
        $collaboratable1 = CollaboratableActor::create([]);
        $collaboratable2 = CollaboratableActor::create([]);
        $collaboratable3 = CollaboratableActor::create([]);
        $collaboratable4 = CollaboratableActor::create([]);
        $collaboratable5 = CollaboratableActor::create([]);

        // collaborators
        $user1 = User::create([]);
        $user2 = User::create([]);
        $user3 = User::create([]);

        // mapping
        $collaborateWithSamples = [
            [
                'collaborator' => $user1,
                'collaborateWiths' => collect([
                    $collaboratable1,
                    $collaboratable2,
                    $collaboratable5,
                ]),
            ],
            [
                'collaborator' => $user2,
                'collaborateWiths' => collect([
                    $collaboratable3,
                    $collaboratable5,
                ]),
            ],
            [
                'collaborator' => $user3,
                'collaborateWiths' => collect([
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
            $collaborator = $sample['collaborator'];
            $collaboratables = $sample['collaborateWiths'];
            foreach ($collaboratables as $collabratable) {
                $collabratable->addCollaborator($collaborator);
            }
        }

        return $collaborateWithSamples;
    }
}
