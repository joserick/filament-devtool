<?php

namespace Joserick\Filament\DevTool\Commands;

use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

class MakeExporterCommand extends \Filament\Actions\Commands\MakeExporterCommand
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
