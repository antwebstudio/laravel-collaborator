<?php

namespace Slizk\Collaborator\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Slizk\Collaborator\HasCollaborators;

class CollaboratableActor extends Model
{
    use HasCollaborators;

    /**
     * @var string
     */
    protected $table = 'actors';

    /**
     * @var array
     */
    protected $fillable = ['created_by'];
}
