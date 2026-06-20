<?php

namespace Joserick\Filament\DevTool;

use Filament\Actions\Commands as ActionsCommands;
use Filament\Commands as FilamentCommands;
use Filament\Forms\Commands as FormsCommands;
use Filament\Infolists\Commands as InfolistsCommands;
use Filament\Schemas\Commands as SchemasCommands;
use Filament\Support\Commands as SupportCommands;
use Filament\Tables\Commands as TablesCommands;
use Filament\Widgets\Commands as WidgetsCommands;
use Joserick\Filament\DevTool\Commands as DevToolCommands;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DevToolServiceProvider extends PackageServiceProvider
{
    /**
     * The package name.
     */
    public static string $name = 'filament-devtool';

    /**
     * Register services.
     *
     * Bind original Filament make commands to our Canvas-aware wrappers
     * so that files are generated inside the package, not the Laravel app.
     */
    public function register(): void
    {
        parent::register();

        $this->registerFilamentCommandBindings();
    }

    /**
     * Bind each original Filament command class to our Canvas-aware wrapper.
     */
    protected function registerFilamentCommandBindings(): void
    {
        $bindings = [
            // Forms
            FormsCommands\MakeFieldCommand::class => DevToolCommands\MakeFieldCommand::class,
            FormsCommands\MakeFormCommand::class => DevToolCommands\MakeFormCommand::class,
            FormsCommands\MakeLivewireFormCommand::class => DevToolCommands\MakeLivewireFormCommand::class,
            FormsCommands\MakeRichContentCustomBlockCommand::class => DevToolCommands\MakeRichContentCustomBlockCommand::class,

            // Tables
            TablesCommands\MakeColumnCommand::class => DevToolCommands\MakeColumnCommand::class,
            TablesCommands\MakeTableCommand::class => DevToolCommands\MakeTableCommand::class,
            TablesCommands\MakeLivewireTableCommand::class => DevToolCommands\MakeLivewireTableCommand::class,

            // Infolists
            InfolistsCommands\MakeEntryCommand::class => DevToolCommands\MakeEntryCommand::class,

            // Schemas
            SchemasCommands\MakeComponentCommand::class => DevToolCommands\MakeComponentCommand::class,
            SchemasCommands\MakeSchemaCommand::class => DevToolCommands\MakeSchemaCommand::class,
            SchemasCommands\MakeLivewireSchemaCommand::class => DevToolCommands\MakeLivewireSchemaCommand::class,

            // Actions
            ActionsCommands\MakeImporterCommand::class => DevToolCommands\MakeImporterCommand::class,
            ActionsCommands\MakeExporterCommand::class => DevToolCommands\MakeExporterCommand::class,

            // Support
            SupportCommands\MakeIssueCommand::class => DevToolCommands\MakeIssueCommand::class,

            // Filament (panel-aware)
            FilamentCommands\MakeResourceCommand::class => DevToolCommands\MakeResourceCommand::class,
            FilamentCommands\MakePageCommand::class => DevToolCommands\MakePageCommand::class,
            WidgetsCommands\MakeWidgetCommand::class => DevToolCommands\MakeWidgetCommand::class,
            FilamentCommands\MakeClusterCommand::class => DevToolCommands\MakeClusterCommand::class,
            FilamentCommands\MakeRelationManagerCommand::class => DevToolCommands\MakeRelationManagerCommand::class,
        ];

        foreach ($bindings as $original => $wrapper) {
            $this->app->bind($original, $wrapper);
        }
    }

    /**
     * Configure the package.
     */
    public function configurePackage(Package $package): void
    {
        $package
            ->name(self::$name)
            ->hasCommands([
                DevToolCommands\BoostSkeletonCommand::class,
            ]);
    }
}
