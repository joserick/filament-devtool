<?php

namespace Joserick\Filament\DevTool\Commands;

use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

class MakeIssueCommand extends \Filament\Support\Commands\MakeIssueCommand
{
    use InteractsWithCanvasPreset;
}
