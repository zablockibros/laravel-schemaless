# Laravel Schemaless

Create and prototype with models without needing to create migrations and alterations to a database schema.

[![License: MIT](https://img.shields.io/badge/License-MIT-brightgreen.svg?style=flat-square)](https://opensource.org/licenses/MIT)

* [Background](#background)
* [Installation](#installation)
* [Copyright and License](#copyright-and-license)


## Why?

When quickly prototyping an API or web app, you will be constantly adding and altering table columns for your model. With Schemaless, you can simply create models and extend the `ZablockiBros\Models\Item` model and define `$extraAttributes`. Create records, set attributes, fill records all the ways you normally would in Laravel.

## Installation

**Requirements**: This package requires PHP 7.1.3 or higher and Laravel 5.7

1. Add the following to the `repositories` section of you `composer.json` file:

    ```sh
    "repositories": [
        {
            "type": "vcs",
            "no-api": true,
            "url": "git@github.com:zablockibros/laravel-jetpack.git"
        }
    ],
    ```
    
2. Install the package via Composer:

    ```sh
    $ composer require zablockibros/laravel-jetpack:dev-master
    ```

    The package will automatically register its service provider.

3. Publish the package:

    ```sh
    php artisan vendor:publish --provider="ZablockiBros\Schemaless\SchemalessServiceProvider"
    ```
## Defining Your Models

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

#### Accessing Attributes

You can access attributes the same way you normally would with an Eloquent model:

```php
$yourModel->name; // 'test'
```

#### Saving Attributes

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

## Copyright and License

[MIT License](LICENSE.md).

Copyright (c) 2019 Justin Zablocki
