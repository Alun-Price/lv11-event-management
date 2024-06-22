<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;

trait CanLoadRelationships
{
    /**
     * Loads the relationships for the given model.
     *
     * @param Model|QueryBuilder|EloquentBuilder $for
     * @param array $relations
     * @return Model|QueryBuilder|EloquentBuilder
     */
    public function loadRelationships(Model|QueryBuilder|EloquentBuilder $for, ?array $relations = null): Model|QueryBuilder|EloquentBuilder
    {
        $relations = $relations ?? $this->relations ?? [];

        foreach ($relations as $relation) {
            $for->when(
                $this->shouldIncludeRelation($relation),
                fn($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation)
            );
        }

        return $for;
    }

    protected function shouldIncludeRelation(string $relation): bool
    {
        $include = request()->query('include');

        if (!$include) {
            return false;
        }

        $relations = array_map('trim', explode(',', $include));


        return in_array($relation, $relations);
    }

}
