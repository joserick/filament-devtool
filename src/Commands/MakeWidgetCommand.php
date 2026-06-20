<?php

namespace Joserick\Filament\DevTool\Commands;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\TableWidget;
use Filament\Widgets\Widget;
use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;

use function Laravel\Prompts\select;

class MakeWidgetCommand extends \Filament\Widgets\Commands\MakeWidgetCommand
{
    use InteractsWithCanvasPreset;

    protected function configureType(): void
    {
        $this->type = match (true) {
            boolval($this->option('chart')) => ChartWidget::class,
            boolval($this->option('stats-overview')) => StatsOverviewWidget::class,
            boolval($this->option('table')) => TableWidget::class,
            default => $this->input->isInteractive()
                ? select(
                    label: 'Which type of widget would you like to create?',
                    options: [
                        Widget::class => 'Custom',
                        ChartWidget::class => 'Chart',
                        StatsOverviewWidget::class => 'Stats overview',
                        TableWidget::class => 'Table',
                    ],
                )
                : null,
        };
    }
}
