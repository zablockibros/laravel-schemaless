<?php

namespace ZablockiBros\Schemaless\Traits;

use ZablockiBros\Schemaless\Scopes\ItemTypeScope;
use Illuminate\Database\Eloquent\Model;

trait HasSchemalessTable
{
    /**
     * @return void
     */
    public static function bootUsesGenericTable()
    {
        static::creating(function (Model &$model) {
            $model->type = $model->type ?: get_class($model);
        });

        // limit query of this model to its type
        static::addGlobalScope(new ItemTypeScope());

        parent::boot();
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return 'items';
    }

    /**
     * @return array
     */
    public function getFillable()
    {
        return array_merge(
            parent::getFillable(),
            [
                'itemable_type',
                'itemable_id',
                'item_type',
            ]
        );
    }
}
