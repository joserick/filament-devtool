<?php

namespace Joserick\Filament\DevTool\Commands\Concerns;

use Orchestra\Canvas\Core\Concerns\CreatesUsingGeneratorPreset;
use ReflectionClass;

use function Orchestra\Sidekick\join_paths;

/**
 * Trait that adapts FilamentPHP make commands to work with Orchestra Canvas
 * presets, enabling file generation within package development contexts.
 *
 * The trait overrides path-resolution methods from Filament's traits
 * (CanAskForComponentLocation, HasPanel, HasResourcesLocation, etc.)
 * to use the Canvas preset instead of application paths or user prompts.
 */
trait InteractsWithCanvasPreset
{
    use CreatesUsingGeneratorPreset;

    /**
     * Configure the command, adding Canvas preset options.
     */
    protected function configure(): void
    {
        parent::configure();

        $this->addGeneratorPresetOptions();
    }

    /**
     * Get the default stub path, resolving to the parent class's stubs directory.
     */
    protected function getDefaultStubPath(): string
    {
        $reflectionClass = new ReflectionClass(get_parent_class($this));

        return (string) str($reflectionClass->getFileName())
            ->beforeLast('Commands')
            ->append('../stubs');
    }

    /**
     * Resolve the component location using the Canvas preset.
     *
     * @param  string  $path  Relative path within Filament (e.g. "Forms/Components")
     * @return array{0: string, 1: string, 2: null}
     */
    protected function askForComponentLocation(string $path, string $question = 'Where would you like to create the component?'): array
    {
        $preset = $this->generatorPreset();
        $pathNamespace = (string) str($path)->replace('/', '\\');

        $namespace = rtrim($preset->rootNamespace(), '\\') . '\\Filament\\' . $pathNamespace;
        $directory = join_paths($preset->sourcePath(), 'Filament', str_replace('\\', '/', $path));

        return [$namespace, $directory, null];
    }

    /**
     * Resolve the Livewire component location using the Canvas preset.
     *
     * @return array{0: string, 1: string, 2: null}
     */
    protected function askForLivewireComponentLocation(string $question = 'Where would you like to create the Livewire component?'): array
    {
        $preset = $this->generatorPreset();

        return [
            rtrim($preset->rootNamespace(), '\\') . '\\Livewire',
            join_paths($preset->sourcePath(), 'Livewire'),
            null,
        ];
    }

    // ─── Panel overrides ───────────────────────────────────────────

    /**
     * Skip panel configuration — panels are not applicable in package development.
     */
    protected function configurePanel(string $question, ?string $initialQuestion = null): void
    {
        $this->panel = null;
    }

    /**
     * Skip cluster FQN configuration — clusters are panel-specific.
     */
    protected function configureClusterFqn(string $initialQuestion, string $question): void
    {
        $this->clusterFqn = null;
    }

    // ─── Location overrides ────────────────────────────────────────

    /**
     * Resolve the resources location using the Canvas preset.
     */
    protected function configureResourcesLocation(string $question): void
    {
        if (filled($this->clusterFqn ?? null)) {
            return;
        }

        $preset = $this->generatorPreset();

        $this->resourcesNamespace = rtrim($preset->rootNamespace(), '\\') . '\\Filament\\Resources';
        $this->resourcesDirectory = join_paths($preset->sourcePath(), 'Filament', 'Resources');
    }

    /**
     * Resolve the pages location using the Canvas preset.
     */
    protected function configurePagesLocation(): void
    {
        if (filled($this->resourceFqn ?? null) || filled($this->clusterFqn ?? null)) {
            return;
        }

        $preset = $this->generatorPreset();

        $this->pagesNamespace = rtrim($preset->rootNamespace(), '\\') . '\\Filament\\Pages';
        $this->pagesDirectory = join_paths($preset->sourcePath(), 'Filament', 'Pages');
    }

    /**
     * Resolve the widgets location using the Canvas preset.
     */
    protected function configureWidgetsLocation(): void
    {
        if (filled($this->resourceFqn ?? null)) {
            return;
        }

        $preset = $this->generatorPreset();

        $this->widgetsNamespace = rtrim($preset->rootNamespace(), '\\') . '\\Filament\\Widgets';
        $this->widgetsDirectory = join_paths($preset->sourcePath(), 'Filament', 'Widgets');
    }

    /**
     * Resolve the clusters location using the Canvas preset.
     */
    protected function configureClustersLocation(): void
    {
        $preset = $this->generatorPreset();

        $this->clustersNamespace = rtrim($preset->rootNamespace(), '\\') . '\\Filament\\Clusters';
        $this->clustersDirectory = join_paths($preset->sourcePath(), 'Filament', 'Clusters');
    }
}
