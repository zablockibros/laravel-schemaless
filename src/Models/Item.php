<?php

namespace ZablockiBros\Schemaless\Models;

use ZablockiBros\Schemaless\Contracts\ValidatesAttributes;
use ZablockiBros\Schemaless\Traits\HasSchemalessAttributes;
use ZablockiBros\Schemaless\Traits\HasSchemalessRelationships;
use ZablockiBros\Schemaless\Traits\HasSchemalessTable;
use ZablockiBros\Schemaless\Traits\ValidatesModelAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model implements ValidatesAttributes
{
    use HasSchemalessTable;
    use HasSchemalessAttributes;
    use HasSchemalessRelationships;
    use ValidatesModelAttributes;
    use SoftDeletes;

    /**
     * @var array
     */
    protected $guarded = [
        'type',
    ];
}
