<?php

namespace M3assy\DataTags;

use M3assy\DataTags\Contracts\DataTagAdapter;
use Illuminate\Support\ServiceProvider;

class DataTagsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__."/database/migrations");
        $this->publishes([
            __DIR__."/config/datatags.php"=>config_path("datatags.php"),
        ], 'datatags-config');

        $this->app->bind(DataTagAdapter::class, function (){
            $defaultAdapter = config("datatags.generators")[config("datatags.default_generator")]["engine"];
            return new $defaultAdapter();
        });
        $this->app->bind("datatag", function (){
            return new DataTagClient(app(DataTagAdapter::class));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__."/config/datatags.php", "datatags");
    }
}
