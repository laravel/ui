<?php

namespace Laravel\Ui;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;

class ControllersCommand extends Command
{
    use SharedCommandMethods;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ui:controllers {guard? : Install authentication guard }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold the authentication controllers';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $path = 'Http/Controllers'.str_replace('\\','/',$this->newGuardNamesapce()).'/Auth';
        $this->isDirAndMake( app_path($path) );
        $filesystem = new Filesystem;

        collect($filesystem->allFiles(__DIR__ . '/../stubs/Auth'))
            ->each(function (SplFileInfo $file) use ($path, $filesystem) {
                $filePath = app_path($path.'/' . Str::replaceLast('.stub', '.php', $file->getFilename()));
                if (file_exists($filePath)) {
                    return ;
                }
                $filesystem->copy(
                    $file->getPathname(),
                    $filePath
                );

                $content = str_replace([
                    '{{namespace}}',
                    '{{ namespaceAuthGuard }}',
                    '{{ AuthNewGuard }}',
                    '{{ viewPrefixPath }}',
                ], [
                    $this->laravel->getNamespace(),
                    $this->newGuardNamesapce(),
                    $this->prefixViewPath(),
                    $this->handleViewPath(),
                ],
                    $filesystem->get($filePath));
                $filesystem->put($filePath, $content);
            });

        $this->info('Authentication scaffolding generated successfully.');
    }

}
