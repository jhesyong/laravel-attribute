<?php

namespace Jhesyong\Attribute;

use Illuminate\Support\ServiceProvider;

class AttributeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->validator->extend(
            'attr',
            'Jhesyong\Attribute\Validator@validate',
            'The :attribute field is invalid.'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Bind singleton
         */
        $this->app->singleton('Jhesyong\Attribute\Registrar');
        $this->app->singleton('Jhesyong\Attribute\Delegate');
        $this->app->singleton('attr', 'Jhesyong\Attribute\Delegate');
    }
}
