<?php

namespace Joserick\Filament\DevTool\Commands;

use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

class MakeImporterCommand extends \Filament\Actions\Commands\MakeImporterCommand
{
    use InteractsWithCanvasPreset;

    public function handle(): int
    {
        if (! $this->input->getOption('model-namespace')) {
            $this->input->setOption('model-namespace', rtrim($this->generatorPreset()->modelNamespace(), '\\'));
        }

        return parent::handle();
    }
}
