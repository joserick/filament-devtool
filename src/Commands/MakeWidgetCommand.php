<?php

namespace Joserick\Filament\DevTool\Commands;

use Filament\Widgets\Widget;
use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

use function Laravel\Prompts\select;

class MakeWidgetCommand extends \Filament\Widgets\Commands\MakeWidgetCommand
{
    use InteractsWithCanvasPreset;

    protected function configureType(): void
    {
        $this->type = match (true) {
            boolval($this->option('chart')) => \Filament\Widgets\ChartWidget::class,
            boolval($this->option('stats-overview')) => \Filament\Widgets\StatsOverviewWidget::class,
            boolval($this->option('table')) => \Filament\Widgets\TableWidget::class,
            default => $this->input->isInteractive()
                ? select(
                    label: 'Which type of widget would you like to create?',
                    options: [
                        Widget::class => 'Custom',
                        \Filament\Widgets\ChartWidget::class => 'Chart',
                        \Filament\Widgets\StatsOverviewWidget::class => 'Stats overview',
                        \Filament\Widgets\TableWidget::class => 'Table',
                    ],
                )
                : null,
        };
    }
}
