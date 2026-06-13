<?php

namespace Joserick\Filament\DevTool\Commands;

use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

class MakeComponentCommand extends \Filament\Schemas\Commands\MakeComponentCommand
{
    use InteractsWithCanvasPreset;
}
