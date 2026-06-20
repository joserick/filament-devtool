<?php

declare(strict_types=1);

namespace Tests;

use Laravel\Prompts\Prompt;
use Orchestra\Canvas\Core\PresetManager;
use Orchestra\Canvas\Core\Presets\Preset;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;

use function Orchestra\Sidekick\join_paths;

abstract class TestCase extends BaseTestCase
{
    use WithWorkbench;

    protected function setUp(): void
    {
        $_SERVER['argv'] ??= [];

        Prompt::interactive(false);

        parent::setUp();

        $this->afterApplicationCreated(function (): void {
            $this->configureCanvasPreset();
        });
    }

    public function ignorePackageDiscoveriesFrom(): array
    {
        return [];
    }

    protected function configureCanvasPreset(): void
    {
        $presetManager = $this->app->make(PresetManager::class);

        $packageRoot = dirname(__DIR__);
        $namespace = 'Joserick\\Filament\\DevTool';

        $presetManager->extend('package', fn () => new class($packageRoot, $namespace) extends Preset
        {
            public function __construct(
                private readonly string $pkgRoot,
                private readonly string $namespace,
            ) {}

            public function name(): string
            {
                return 'package';
            }

            public function basePath(): string
            {
                return $this->pkgRoot;
            }

            public function sourcePath(): string
            {
                return join_paths($this->pkgRoot, 'src');
            }

            public function testingPath(): string
            {
                return join_paths($this->pkgRoot, 'tests');
            }

            public function resourcePath(): string
            {
                return join_paths($this->pkgRoot, 'resources');
            }

            public function viewPath(): string
            {
                return join_paths($this->pkgRoot, 'resources', 'views');
            }

            public function factoryPath(): string
            {
                return join_paths($this->pkgRoot, 'database', 'factories');
            }

            public function migrationPath(): string
            {
                return join_paths($this->pkgRoot, 'database', 'migrations');
            }

            public function seederPath(): string
            {
                return join_paths($this->pkgRoot, 'database', 'seeders');
            }

            public function rootNamespace(): string
            {
                return $this->namespace;
            }

            public function commandNamespace(): string
            {
                return $this->namespace.'\\Commands';
            }

            public function modelNamespace(): string
            {
                return $this->namespace;
            }

            public function providerNamespace(): string
            {
                return $this->namespace;
            }

            public function testingNamespace(): string
            {
                return 'Tests';
            }

            public function factoryNamespace(): string
            {
                return $this->namespace.'\\Database\\Factories';
            }

            public function seederNamespace(): string
            {
                return $this->namespace.'\\Database\\Seeders';
            }

            public function hasCustomStubPath(): bool
            {
                return false;
            }

            public function stubPath(): ?string
            {
                return null;
            }
        });

        $presetManager->setDefaultDriver('package');
    }
}
