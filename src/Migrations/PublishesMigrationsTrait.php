<?php

namespace Arhitov\PackageHelpers\Migrations;

use Generator;
use Illuminate\Support\Str;

trait PublishesMigrationsTrait
{
    /**
     * Searches migrations and publishes them as assets.
     *
     * @param string $directory
     * @param string $tabName
     * @return void
     */
    protected function registerMigrations(string $directory, string $tabName = 'migrations'): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                iterator_to_array(
                    (function() use ($directory): Generator {
                        foreach ($this->app->make('files')->allFiles($directory) as $file) {
                            yield $file->getPathname() => $this->app->databasePath(
                                'migrations/' . now()->format('Y_m_d_His') . Str::after($file->getFilename(), '00_00_00_000000')
                            );
                        }
                    })()
                ),
                $tabName
            );
        }
    }
}