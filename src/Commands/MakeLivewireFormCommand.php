<?php

namespace Joserick\Filament\DevTool\Commands;

use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

class MakeLivewireFormCommand extends \Filament\Forms\Commands\MakeLivewireFormCommand
{
    use InteractsWithCanvasPreset;
}
