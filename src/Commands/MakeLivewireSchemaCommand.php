<?php

namespace Joserick\Filament\DevTool\Commands;

use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

class MakeLivewireSchemaCommand extends \Filament\Schemas\Commands\MakeLivewireSchemaCommand
{
    use InteractsWithCanvasPreset;
}
