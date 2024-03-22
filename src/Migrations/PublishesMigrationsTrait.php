<?php

namespace Arhitov\PackageHelpers\Migrations;

use Generator;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;

trait PublishesMigrationsTrait
{
    /**
     * Searches migrations and publishes them as assets.
     *
     * @param string $directory
     *
     * @return void
     * @throws BindingResolutionException
     */
    protected function registerMigrations(string $directory): void
    {
        if ($this->app->runningInConsole()) {
            $generator = function(string $directory): Generator {
                foreach ($this->app->make('files')->allFiles($directory) as $file) {
                    yield $file->getPathname() => $this->app->databasePath(
                        'migrations/' . now()->format('Y_m_d_His') . Str::after($file->getFilename(), '00_00_00_000000')
                    );
                }
            };

            $this->publishes(iterator_to_array($generator($directory)), 'migrations');
        }
    }
}