<?php

namespace Joserick\Filament\DevTool\Commands;

use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

class MakeImporterCommand extends \Filament\Actions\Commands\MakeImporterCommand
{
    use InteractsWithCanvasPreset;
}
