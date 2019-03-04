<?php

namespace ZablockiBros\Schemaless\Models;

use ZablockiBros\Schemaless\Traits\HasSchemalessAttributes;
use ZablockiBros\Schemaless\Traits\HasSchemalessRelationships;
use ZablockiBros\Schemaless\Traits\HasSchemalessTable;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasSchemalessTable;
    use HasSchemalessAttributes;
    use HasSchemalessRelationships;

    /**
     * @var array
     */
    protected $guarded = [
        'type',
    ];
}
