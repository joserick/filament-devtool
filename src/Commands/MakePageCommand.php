<?php

namespace Joserick\Filament\DevTool\Commands;

use Filament\Support\Commands\Exceptions\FailureCommandOutput;
use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

class MakePageCommand extends \Filament\Commands\MakePageCommand
{
    use InteractsWithCanvasPreset;

    public function handle(): int
    {
        try {
            $this->configureFqnEnd();
            $this->configurePanel(question: '');
            $this->configureHasResource();
            $this->configureCluster();
            $this->configureResource();
            $this->configureResourcePageType();
            $this->configurePagesLocation();

            $this->configureLocation();

            $this->createCustomPage();
            $this->createResourceCustomPage();
            $this->createResourceCreatePage();
            $this->createResourceEditPage();
            $this->createResourceViewPage();
            $this->createResourceManageRelatedRecordsPage();
            $this->createView();
        } catch (FailureCommandOutput) {
            return static::FAILURE;
        }

        $this->components->info("Filament page [{$this->fqn}] created successfully.");

        if (filled($this->resourceFqn)) {
            $this->components->info("Make sure to register the page in [{$this->resourceFqn}::getPages()].");
        } else {
            $this->components->info('Make sure to register the page with [pages()] or discover it with [discoverPages()] in the panel service provider.');
        }

        return static::SUCCESS;
    }
}
