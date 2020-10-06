<?php

namespace Laravel\Ui\Presets;

use Illuminate\Filesystem\Filesystem;

class Default extends Preset
{
    /**
     * Install the preset.
     *
     * @return void
     */
    public static function install()
    {
        static::updatePackages();
        static::updateWebpackConfiguration();
        static::updateSass();
        static::updateBootstrapping();
        static::removeNodeModules();
    }

    /**
     * Update the given package array.
     *
     * @param  array  $packages
     * @return array
     */
    protected static function updatePackageArray(array $packages)
    {
        Arr::except($packages, [
            'vue',
            'vue-template-compiler',
            'vue-router',
            'vuex',
            'vue-axios',
            '@babel/preset-react',
            'react',
            'react-dom',
            '@shopify/app-bridge',
            '@shopify/app-bridge-utils',
        ]);
    }

    /**
     * Update the Webpack configuration.
     *
     * @return void
     */
    protected static function updateWebpackConfiguration()
    {
        copy(__DIR__.'/default-stubs/webpack.mix.js', base_path('webpack.mix.js'));
    }

    /**
     * Update the Sass files for the application.
     *
     * @return void
     */
    protected static function updateSass()
    {
        (new Filesystem)->ensureDirectoryExists(resource_path('css'));

        copy(__DIR__.'/default-stubs/app.css', resource_path('css/app.css'));
    }

    /**
     * Update the bootstrapping files.
     *
     * @return void
     */
    protected static function updateBootstrapping()
    {
        copy(__DIR__.'/default-stubs/bootstrap.js', resource_path('js/bootstrap.js'));
    }
}
