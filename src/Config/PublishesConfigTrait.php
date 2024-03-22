<?php

namespace Arhitov\PackageHelpers\Config;

use Generator;

trait PublishesConfigTrait
{
    /**
     * Searches migrations and publishes them as assets.
     *
     * @param string $directory
     *
     * @return void
     */
    protected function registerConfig(string $directory): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                iterator_to_array(
                    (function() use ($directory): Generator {
                        foreach ($this->app->make('files')->allFiles($directory) as $file) {
                            yield $file->getPathname() => $this->app->configPath($file->getFilename());
                        }
                    })()
                ),
                'config'
            );
        }
    }
}