<?php

namespace ZablockiBros\Schemaless\Models;

use ZablockiBros\Schemaless\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field extends Model
{
    use HasSchemalessAttributes;
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'fieldable_type',
        'fieldable_id',
        'name',
        'columns',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'columns' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function fieldable()
    {
        return $this->morphTo('fieldable');
    }
}
