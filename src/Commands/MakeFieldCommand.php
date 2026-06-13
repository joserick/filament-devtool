<?php

namespace Joserick\Filament\DevTool\Commands;

use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

class MakeFieldCommand extends \Filament\Forms\Commands\MakeFieldCommand
{
    use InteractsWithCanvasPreset;
}
