<?php

namespace Joserick\Filament\DevTool\Commands;

use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

class MakeExporterCommand extends \Filament\Actions\Commands\MakeExporterCommand
{
    use InteractsWithCanvasPreset;
}
