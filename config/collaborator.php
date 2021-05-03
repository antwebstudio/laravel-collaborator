<?php
return [
    'pivot' => [
        'table' => 'collaboratables',
        'morphname' => 'collaboratable',
        'columns' => [
            'collaboratable_type' => 'collaboratable_type',
            'collaboratable_id'   => 'collaboratable_id',
            'collaborator_type'   => 'collaborator_type',
            'collaborator_id'     => 'collaborator_id',
            'is_owner'            => 'is_owner',
        ],
    ],
    'owner' => [
        'created_by_as_default' => true,
        'columns' => [
            'created_by' => 'created_by'
        ],
    ],
    'models' => [
        'user' => \App\Models\User::class,
    ],
];
