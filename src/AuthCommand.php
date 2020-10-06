<?php

namespace Laravel\Ui;

use Illuminate\Console\Command;
use InvalidArgumentException;

class AuthCommand extends Command
{
    use SharedCommandMethods;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ui:auth
                    { type=bootstrap : The preset type (bootstrap) }
                    {guard? : Install authentication guard }
                    {--views : Only scaffold the authentication views}
                    {--force : Overwrite existing views by default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold basic login and registration views and routes';

    /**
     * The views that need to be exported.
     *
     * @var array
     */
    protected $views = [
        'auth/login.stub' => 'auth/login.blade.php',
        'auth/passwords/confirm.stub' => 'auth/passwords/confirm.blade.php',
        'auth/passwords/email.stub' => 'auth/passwords/email.blade.php',
        'auth/passwords/reset.stub' => 'auth/passwords/reset.blade.php',
        'auth/register.stub' => 'auth/register.blade.php',
        'auth/verify.stub' => 'auth/verify.blade.php',
        'home.stub' => 'home.blade.php',
        'layouts/app.stub' => 'layouts/app.blade.php',
    ];

    /**
     * Execute the console command.
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function handle()
    {
        if (static::hasMacro($this->argument('type'))) {
            return call_user_func(static::$macros[$this->argument('type')], $this);
        }

        if (!in_array($this->argument('type'), ['bootstrap'])) {
            throw new InvalidArgumentException('Invalid preset.');
        }

        $this->ensureDirectoriesExist();
        $this->exportViews();

        if (!$this->option('views')) {
            $this->exportBackend();
        }

        $this->info('Authentication scaffolding generated successfully.');
    }

    /**
     * Create the directories for the files.
     *
     * @return void
     */
    protected function ensureDirectoriesExist()
    {
        $this->isDirAndMake($this->getViewPath($this->prefixViewPath(). '/layouts'));
        $this->isDirAndMake($this->getViewPath($this->prefixViewPath(). '/auth/passwords'));
    }

    /**
     * Get full view path relative to the application's configured view path.
     *
     * @param string $path
     * @return string
     */
    protected function getViewPath($path)
    {
        return implode(DIRECTORY_SEPARATOR, [
            config('view.paths')[0] ?? resource_path('views'), $path,
        ]);
    }

    /**
     * Export the authentication views.
     *
     * @return void
     */
    protected function exportViews()
    {
        foreach ($this->views as $key => $value) {
            $value = $this->prefixViewPath() . DIRECTORY_SEPARATOR . $value;
            if (file_exists($view = $this->getViewPath($value)) && !$this->option('force')) {
                if (!$this->confirm("The [{$value}] view already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            copy(
                __DIR__ . '/Auth/' . $this->argument('type') . '-stubs/' . $key,
                $view
            );
            $content = str_replace([
                '{{authGuard}}',
                '{{view}}'
            ], [
                $this->getGuardName(),
                $this->handleViewPath(),
            ], file_get_contents($view));

            file_put_contents($view, $content);
        }
    }

    /**
     * Export the authentication backend.
     *
     * @return void
     */
    protected function exportBackend()
    {
        $this->callSilent('ui:controllers', [
            "guard" => $this->getGuardName()
        ]);
        $directory = app_path('Http/Controllers' . str_replace('\\', '/', $this->newGuardNamesapce()));
        $controller = $directory . '/HomeController.php';

        if (file_exists($controller) && !$this->option('force')) {
            if ($this->confirm("The [HomeController.php] file already exists. Do you want to replace it?")) {
                $this->isDirAndMake($directory);
                file_put_contents($controller, $this->compileControllerStub());
            }
        } else {
            $this->isDirAndMake($directory);
            file_put_contents($controller, $this->compileControllerStub());
        }
        $routes = str_replace(
            [
                '{{namespace}}',
                '{{ namespaceAuthGuard }}',
                '{{authGuard}}',
                '{{view}}',
            ],
            [
                $this->laravel->getNamespace(),
                $this->newGuardNamesapce(),
                $this->getGuardName(),
                $this->handleViewPath(),
            ],
            file_get_contents(__DIR__ . '/Auth/stubs/routes.stub')
        );

        file_put_contents(
            base_path('routes/web.php'),
            $routes,
            FILE_APPEND
        );

        copy(
            __DIR__ . '/../stubs/migrations/2014_10_12_100000_create_password_resets_table.php',
            base_path('database/migrations/2014_10_12_100000_create_password_resets_table.php')
        );
    }

    /**
     * Compiles the "HomeController" stub.
     *
     * @return string
     */
    protected function compileControllerStub()
    {
        return str_replace([
            '{{namespace}}', '{{namespaceAuthGuard}}', '{{view}}', '{{newAuthGuard}}'
        ], [
            $this->laravel->getNamespace(), $this->newGuardNamesapce(), $this->handleViewPath(), $this->getGuardName()
        ],
            file_get_contents(__DIR__ . '/Auth/stubs/controllers/HomeController.stub')
        );
    }

}
