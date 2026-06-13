<?php

namespace Joserick\Filament\DevTool\Commands;

use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

class MakeColumnCommand extends \Filament\Tables\Commands\MakeColumnCommand
{
    use InteractsWithCanvasPreset;
}
