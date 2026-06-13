<?php

namespace Joserick\Filament\DevTool\Commands;

use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

class MakeRichContentCustomBlockCommand extends \Filament\Forms\Commands\MakeRichContentCustomBlockCommand
{
    use InteractsWithCanvasPreset;
}
