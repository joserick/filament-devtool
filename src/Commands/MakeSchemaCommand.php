<?php

namespace Joserick\Filament\DevTool\Commands;

use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

class MakeSchemaCommand extends \Filament\Schemas\Commands\MakeSchemaCommand
{
    use InteractsWithCanvasPreset;
}
