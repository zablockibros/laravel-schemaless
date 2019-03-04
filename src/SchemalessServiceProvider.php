<?php

namespace ZablockiBros\Schemaless;

use Illuminate\Support\ServiceProvider;

class SchemalessServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap method
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }
    }

    /**
     * Register the package's publishable resources.
     */
    protected function registerPublishing()
    {
        // migrations
        if (! class_exists('CreateSchemalessTables')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../database/migrations/create_schemaless_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_schemaless_tables.php'),
            ], 'migrations');
        }
    }

    /**
     * Register method
     */
    public function register()
    {
        //
    }
}
