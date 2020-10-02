<?php

namespace Laravel\Ui\Presets;

use Illuminate\Filesystem\Filesystem;

class Tailwind extends Preset
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
        static::updateCss();
        static::updateTailwindConfiguration();
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
        return [
            '@tailwindcss/ui' => '^0.5.0',
            'postcss-import' => '^12.0.1',
            'tailwindcss' => '^1.3.0',
        ] + $packages;
    }

    /**
     * Update the Webpack configuration.
     *
     * @return void
     */
    protected static function updateWebpackConfiguration()
    {
        copy(__DIR__.'/tailwind-stubs/webpack.mix.js', base_path('webpack.mix.js'));
    }

    /**
     * Update the Css files for the application.
     *
     * @return void
     */
    protected static function updateCss()
    {
        (new Filesystem)->ensureDirectoryExists(resource_path('css'));

        copy(__DIR__.'/tailwind-stubs/app.css', resource_path('css/app.css'));
    }

    /**
     * Update the Tailwind configuration
     *
     * @return void
     */
    protected static function updateTailwindConfiguration()
    {
        copy(__DIR__.'/tailwind-stubs/tailwindcss-config.js', base_path('tailwindcss-config.js'));
    }
}
