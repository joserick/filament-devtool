<?php

namespace Joserick\Filament\DevTool\Commands;

use Filament\Support\Commands\Exceptions\FailureCommandOutput;
use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

class MakeResourceCommand extends \Filament\Commands\MakeResourceCommand
{
    use InteractsWithCanvasPreset;

    public function handle(): int
    {
        if (! $this->input->getOption('model-namespace')) {
            $this->input->setOption('model-namespace', rtrim($this->generatorPreset()->modelNamespace(), '\\'));
        }

        if (! $this->input->getOption('resource-namespace')) {
            $this->input->setOption('resource-namespace', rtrim($this->generatorPreset()->rootNamespace(), '\\') . '\\Filament\\Resources');
        }

        try {
            $this->configureModel();
            $this->configureRecordTitleAttribute();
            $this->configurePanel(question: '');
            $this->configureIsSimple();
            $this->configureIsNested();
            $this->configureCluster();
            $this->configureResourcesLocation(question: '');
            $this->configureParentResource();
            $this->configureHasViewOperation();
            $this->configureIsGenerated();
            $this->configureIsSoftDeletable();
            $this->configureHasResourceClassesOutsideDirectories();

            $this->configureLocation();
            $this->configurePageRoutes();

            $this->createFormSchema();
            $this->createInfolistSchema();
            $this->createTable();

            $this->createResourceClass();

            $this->createManagePage();
            $this->createListPage();
            $this->createCreatePage();
            $this->createEditPage();
            $this->createViewPage();
        } catch (FailureCommandOutput) {
            return static::FAILURE;
        }

        $this->components->info("Filament resource [{$this->fqn}] created successfully.");

        $this->components->info('Make sure to register the resource with [resources()] or discover it with [discoverResources()] in the panel service provider.');

        return static::SUCCESS;
    }
}
