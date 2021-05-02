<?php

namespace Slizk\Collaborator;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait HasCollaborators
{
    /**
     * @return mixed
     */
    public function scopeCollaboratedWith(Builder $builder, Model $user)
    {
        $model = $this->getModel();
        $class = get_class($model);
        $table = config('collaborator.pivot.table');

        $collaboratable_ids = DB::table($table)
            ->where(config('collaborator.pivot.columns.collaborator_id'), '=', $user->id)
            ->where(config('collaborator.pivot.columns.collaboratable_type'), '=', $class)
            ->pluck('collaboratable_id')
        ;

        $builder->whereIn('id', $collaboratable_ids);
    }

    /**
     * @return mixed
     */
    public function scopeOwnedBy(Builder $builder, Model $user)
    {
        $model = $this->getModel();
        $class = get_class($model);
        $table = config('collaborator.pivot.table');

        $collaboratable_ids = DB::table($table)
            ->where(config('collaborator.pivot.columns.collaborator_id'), '=', $user->id)
            ->where(config('collaborator.pivot.columns.collaboratable_type'), '=', $class)
            ->where(config('collaborator.pivot.columns.is_owner'), '=', true)
            ->pluck('collaboratable_id')
        ;

        $builder->whereIn('id', $collaboratable_ids);
    }

    /**
     * @return self
     */
    public function addCollaborator(Model $user)
    {
        $model = $this->getModel();
        $class = get_class($user);
        $model->collaborators()->save($user, ['collaborator_type' => $class]);
        $model->load('collaborators');
        return $this;
    }

    /**
     * @return self
     */
    public function addCollaborators(array $users)
    {
        foreach ($users as $user) {
            $this->addCollaborator($user);
        }
        return $this;
    }

    /**
     * @return self
     */
    public function removeCollaborator(Model $user)
    {
        $model = $this->getModel();
        $model->collaborators()->detach($user);
        $model->load('collaborators');
        return $this;
    }

    /**
     * @return self
     */
    public function removeCollaborators(array $users)
    {
        foreach ($users as $user) {
            $this->removeCollaborator($user);
        }
        return $this;
    }

    /**
     * @return MorphToMany
     */
    public function collaborators()
    {
        return $this->morphToMany(
            static::userClass(),
            config('collaborator.pivot.morphname'), // 'collaboratable',
            null,
            config('collaborator.pivot.columns.collaboratable_id'), //'collaboratable_id',
            config('collaborator.pivot.columns.collaborator_id'), //'collaborator_id'
        )
        ->withPivot(config('collaborator.pivot.columns.is_owner'))
        ->withTimestamps();
    }

    /**
     * @return boolean
     */
    public function hasCollaborator(Model $user)
    {
        $model = $this->getModel();
        return $model->collaborators()->where(config('collaborator.pivot.columns.collaborator_id'), '=', $user->id)->exists();
    }

    /**
     * @return boolean
     */
    public function isOwnedBy($user)
    {
        $model = $this->getModel();
        return $model->owners->contains($user);
    }

    /**
     * @return self
     */
    public function addCollaboratorOwner(Model $user)
    {
        $model = $this->getModel();
        $found = $model
            ->collaborators()
            ->where(config('collaborator.pivot.columns.collaborator_id'), '=', $user->id)
            ->exists();
        if (!$found) {
            $model->addCollaborator($user);
        }
        $model->collaborators()->updateExistingPivot($user->id, [config('collaborator.pivot.columns.is_owner') => true]);
        return $this;
    }

    /**
     * @return self
     */
    public function removeCollaboratorOwner(Model $user)
    {
        $model = $this->getModel();
        $found = $model
            ->collaborators()
            ->where(config('collaborator.pivot.columns.collaborator_id'), '=', $user->id)
            ->exists();
        if ($found) {
            $model->collaborators()->updateExistingPivot($user->id, [config('collaborator.pivot.columns.is_owner') => false]);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOwnerAttribute()
    {
        $model = $this->getModel();
        return $model->owners->first();
    }

    /**
     * @return Collection
     */
    public function getOwnersAttribute()
    {
        $model = $this->getModel();
        $owners = $model->collaborators()->wherePivot(config('collaborator.pivot.columns.is_owner'), true)->get();
        if ((bool) config('collaborator.owner.created_by_as_default') && $model->defaultOwner && !$owners->contains($model->defaultOwner)) {
            $owners->prepend($model->defaultOwner);
        }
        return $owners;
    }

    public function defaultOwner()
    {
        return $this->belongsTo(static::userClass(), config('collaborator.owner.columns.created_by'));
    }

    /**
     * @return string
     */
    protected static function userClass()
    {
        return config('collaborator.models.user');
    }
}
