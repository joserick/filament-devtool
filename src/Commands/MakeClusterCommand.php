<?php

namespace Joserick\Filament\DevTool\Commands;

use Filament\Support\Commands\Exceptions\FailureCommandOutput;
use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

class MakeClusterCommand extends \Filament\Commands\MakeClusterCommand
{
    use InteractsWithCanvasPreset;

    public function handle(): int
    {
        try {
            $this->configureHasClusterClassesOutsideDirectories();
            $this->configureFqnEnd();
            $this->configurePanel(question: '');
            $this->configureClustersLocation();

            $this->configureFqn();

            $this->createClass();
        } catch (FailureCommandOutput) {
            return static::FAILURE;
        }

        $this->components->info("Filament cluster [{$this->fqn}] created successfully.");

        $this->components->info('Make sure to register the cluster with [clusters()] or discover it with [discoverClusters()] in the panel service provider.');

        return static::SUCCESS;
    }
}
