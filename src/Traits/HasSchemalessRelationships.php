<?php

namespace ZablockiBros\Schemaless\Traits;

use ZablockiBros\Schemaless\Models\Item;

trait HasSchemalessRelationships
{
    /**
     * @param string $class
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function itemable(string $class = Item::class)
    {
        return $this->morphTo($this->getItemMorphColumnName())
            ->where($this->getItemMorphColumnName() . '_type', $class);
    }

    /**
     * @param string $class
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function item(string $class = Item::class)
    {
        return $this->morphOne(Item::class, $this->getItemMorphColumnName());
    }

    /**
     * @param null|string $type
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function items(string $class = Item::class)
    {
        return $this->morphMany(Item::class, $this->getItemMorphColumnName());
    }

    /**
     * @param null|string $type
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function manyItems(string $class = Item::class)
    {
        return $this->morphToMany(
            $class,
            'child',
            'item_items',
            'child_id',
            'parent_id'
        )
            ->withPivot('parent_type', 'parent_id', 'child_type', 'child_id')
            ->wherePivot('parent_type', get_class($this))
            ->withPivotValue('parent_type', get_class($this));
    }

    /**
     * @param string $class
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function byManyItems(string $class = Item::class)
    {
        return $this->morphedByMany(
            $class,
            'parent',
            'item_items',
            'parent_id',
            'child_id'
        )
            ->withPivot('parent_type', 'parent_id', 'child_type', 'child_id')
            ->wherePivot('child_type', get_class($this))
            ->withPivotValue('child_type', get_class($this));
    }

    /**
     * @override
     *
     * @return string
     */
    public function getItemMorphColumnName()
    {
        return 'itemable';
    }
}
