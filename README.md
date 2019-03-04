# Laravel Schemaless

Create and prototype with models without needing to create migrations and alterations to a database schema.

## Why?

When quickly prototyping an API or web app, you will be constantly adding and altering table columns and relationships to your model. With Schemaless, you can simply create models and extend the `ZablockiBros\Models\Item` model and define `$extraAttributes`. Create records, set attributes, fill records all the ways you normally would in Laravel.

## Installation

**Requirements**: This package requires PHP 7.1.3 or higher and Laravel 5.7

1. Install the package via Composer:

    ```sh
    $ composer require zablockibros/laravel-schemaless
    ```

    The package will automatically register its service provider.

2. Publish the package:

    ```sh
    php artisan vendor:publish --provider="ZablockiBros\Schemaless\SchemalessServiceProvider"
    ```

3. Run migrations:
    ```sh
    php artisan migrate
    ```
    
## Configuring Your Models

You can use schemaless attributes and relationships using your own tables or the migration-provided `items` tables and the base `Item` model class.

### Using Item Base Model

1. Create your model:
    ```sh
    php artisan make:model Models/YourModel
    ```

2. Extend the `ZablockiBros\Models\Item` class:
    ```php
    class YourModel extends \ZablockiBros\Models\Item
    ```

3. Define your schemaless attributes:
    ```php
    <?php
     
    namespace App\Models;
     
    use ZablockiBros\Schemaless\Models\Item;
     
    class YourModel extends Item
    {
        /**
         * @var array
         */
        protected $extraAttributes = [
           'name'    => null, // attribute 'name' with default null
           'options' => null,
           'qty'     => 0,
        ];
     
        /**
         * @var array
         */
        protected $casts = [
           'options' => 'array',
        ];
    }
    ```

### Using Your Own Table

You are able to use your own existing tables and models with schemaless attributes, relationships, and tables.

Schemaless provides three core traits:
- `HasSchemalessAttributes`, `HasSchemalessRelationships`, and `HasSchemalessTable`.
    
To setup your model:
1. Create your table and model (only create a new table if you are NOT using `HasSchemalessTable`).
2. Add the desired traits to your model.
3. Configure your `$extraAttributes` and relations as described above.

Notes:
* If you are using `HasSchemalessAttributes`, make sure your table has a `json` type column named `columns`. If you want to name it something else, override the column name with the following method on your model:
    ```php
    /**
     * @return string
     */
    protected function getExtraAttributesColumnName()
    {
        return 'my_columns';
    }
    ```
* If you are using `HasSchemalessRelations`, make sure your table has a `morphs` column called `itemable` (or your own name). If you use a different morph name, override the following on your model:
    ```php
    /**
     * @return string
     */
    public function getItemMorphColumnName()
    {
        return 'itemable';
    }
    ```
* If you are using the `HasSchemalessTable` trait, you must use the `items` table for your schemaless model.

### Accessing Attributes

You can access attributes the same way you normally would with an Eloquent model:

```php
$yourModel->name; // 'test'
```

### Saving Attributes

Save attributes the same way as well:

```php
YourModel::create([
    'name' => 'test',
]);
 
YourModel::firstOrNew([
    'name' => 'test',
]);
 
$yourModel->fill([
    'name' => 'test',
]);
 
YourModel::where(...)
    ->update([
        'name' => 'test',
    ]);
 
$yourModel->name = 'test';
$yourModel->save();
```

### Relationships

In progress

### Note: Querying Attributes

Schemaless attributes need to be queried at the `json` column in the models table (`items` in the case where the model extends `Item`).

```php
YourModel::where('columns->name', 'test');
```

## Copyright and License

[MIT License](LICENSE.md).

Copyright (c) 2019 Justin Zablocki
