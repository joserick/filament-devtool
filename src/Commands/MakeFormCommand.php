<?php

namespace Joserick\Filament\DevTool\Commands;

use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

class MakeFormCommand extends \Filament\Forms\Commands\MakeFormCommand
{
    use InteractsWithCanvasPreset;
}
