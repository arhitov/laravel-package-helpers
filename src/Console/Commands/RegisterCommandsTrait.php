<?php

namespace Arhitov\PackageHelpers\Console\Commands;

use Generator;
use Illuminate\Support\Str;

trait RegisterCommandsTrait
{
    public function registerCommands(string $namespace, string $directory)
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                iterator_to_array(
                    (function() use ($directory, $namespace): Generator {
                        /** @var \Symfony\Component\Finder\SplFileInfo $file */
                        foreach ($this->app->make('files')->allFiles($directory) as $file) {
                            yield str_replace('\\\\', '\\',
                                $namespace . str_replace(
                                    ['/', '.php'],
                                    ['\\', ''],
                                    Str::after($file->getRealPath(), realpath($directory))
                                ));
                        }
                    })()
                ),
            );
        }
    }
}
