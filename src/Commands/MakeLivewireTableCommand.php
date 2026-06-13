<?php

namespace Joserick\Filament\DevTool\Commands;

use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

class MakeLivewireTableCommand extends \Filament\Tables\Commands\MakeLivewireTableCommand
{
    use InteractsWithCanvasPreset;
}
