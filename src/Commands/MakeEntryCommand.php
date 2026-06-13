<?php

namespace Joserick\Filament\DevTool\Commands;

use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

class MakeEntryCommand extends \Filament\Infolists\Commands\MakeEntryCommand
{
    use InteractsWithCanvasPreset;
}
