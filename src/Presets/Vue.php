<?php

namespace Laravel\Ui\Presets;

use Illuminate\Support\Arr;
use Illuminate\Filesystem\Filesystem;

class Vue extends Preset
{
    /**
     * NPM Package key.
     *
     * @var string
     */
    protected static $packageKey = 'vue';

    /**
     * Install the preset.
     *
     * @return void
     */
    public static function install()
    {
        static::ensureComponentDirectoryExists();
        static::updatePackages();
        static::updateWebpackConfiguration();
        static::updateBootstrapping();
        static::updateComponent();
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
            'resolve-url-loader' => '2.3.1',
            'sass' => '^1.20.1',
            'sass-loader' => '7.*',
            'vue' => '^2.5.17',
            'vue-template-compiler' => '^2.6.10',
        ] + Arr::except($packages, [
            '@babel/preset-react',
            'react',
            'react-dom',
        ]);
    }

    /**
     * Update the Webpack configuration.
     *
     * @return void
     */
    protected static function updateWebpackConfiguration()
    {
        $stubFile = Tailwind::installed() ? 'tailwind.webpack.mix.js' : 'webpack.mix.js';

        copy(__DIR__.'/vue-stubs/'.$stubFile, base_path('webpack.mix.js'));
    }

    /**
     * Update the example component.
     *
     * @return void
     */
    protected static function updateComponent()
    {
        (new Filesystem)->delete(
            resource_path('js/components/Example.js')
        );

        $stubFile = Tailwind::installed() ? 'ExampleComponent.tailwind.vue' : 'ExampleComponent.vue';

        copy(
            __DIR__.'/vue-stubs/'.$stubFile,
            resource_path('js/components/ExampleComponent.vue')
        );
    }

    /**
     * Update the bootstrapping files.
     *
     * @return void
     */
    protected static function updateBootstrapping()
    {
        copy(__DIR__.'/vue-stubs/app.js', resource_path('js/app.js'));
    }
}
