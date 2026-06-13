<?php

namespace Joserick\Filament\DevTool\Commands;

use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

class MakeTableCommand extends \Filament\Tables\Commands\MakeTableCommand
{
    use InteractsWithCanvasPreset;
}
