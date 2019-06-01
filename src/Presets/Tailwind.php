<?php

namespace Laravel\Ui\Presets;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

class Tailwind extends Preset
{
    /**
     * NPM Package key.
     *
     * @var string
     */
    protected static $packageKey = 'tailwindcss';

    /**
     * Install the preset.
     *
     * @return void
     */
    public static function install()
    {
        static::updatePackages();
        static::updateSass();
        static::updateBootstrapping();
        static::removeBootstrapAssets();
        static::updateWebpackConfiguration();
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
            'tailwindcss' => '^1.0.2',
        ] + Arr::except($packages, [
            'bootstrap',
            'jquery',
            'popper.js',
        ]);
    }

    /**
     * Update the Webpack configuration.
     *
     * @return void
     */
    public static function updateWebpackConfiguration()
    {
        $stubsFolder = React::installed() ? 'react-stubs' : 'vue-stubs';

        copy(__DIR__.'/'.$stubsFolder.'/tailwind.webpack.mix.js', base_path('webpack.mix.js'));
    }

    /**
     * Update the Sass files for the application.
     *
     * @return void
     */
    protected static function updateSass()
    {
        copy(__DIR__.'/tailwind-stubs/app.scss', resource_path('sass/app.scss'));
    }

    /**
     * Update the bootstrapping files.
     *
     * @return void
     */
    protected static function updateBootstrapping()
    {
        copy(__DIR__.'/tailwind-stubs/bootstrap.js', resource_path('js/bootstrap.js'));
    }

    /**
     * Remove Bootstrap preset assets if present.
     *
     * @return void
     */
    protected static function removeBootstrapAssets()
    {
        (new Filesystem)->delete(
            resource_path('sass/_variables.scss')
        );
    }
}
