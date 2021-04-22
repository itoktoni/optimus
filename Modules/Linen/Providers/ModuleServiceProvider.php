<?php

namespace Modules\Linen\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Config;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->bind('outstanding_facades', function () {
            return new \Modules\Linen\Dao\Repositories\OutstandingRepository();
        });
        $this->app->bind('master_outstanding_facades', function () {
            return new \Modules\Linen\Dao\Models\MasterOutstanding();
        });
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('Linen.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'Linen'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/Linen');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/Linen';
        }, Config::get('view.paths')), [$sourcePath]), 'Linen');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/Linen');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'Linen');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'Linen');
        }
    }

    /**
     * Register an additional directory of Repositories.
     *
     * @return void
     */
    public function registerFactories()
    {
        // if (! app()->environment('production')) {
        //     app(Factory::class)->load(__DIR__ . '/../Database/factories');
        // }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}