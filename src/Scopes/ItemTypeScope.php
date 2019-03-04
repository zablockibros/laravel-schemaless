<?php

namespace ZablockiBros\Schemaless\Scopes;

use ZablockiBros\Schemaless\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;

class ItemTypeScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model   $model
     */
    public function apply(Builder $builder, Model $model)
    {
        if (! $model instanceof Item) {
            return;
        }

        $builder->where('item_type', get_class($model));
    }
}
