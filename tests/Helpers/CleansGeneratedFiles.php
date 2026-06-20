<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Illuminate\Support\Collection;

trait CleansGeneratedFiles
{
    /**
     * Track files to clean up after each test.
     *
     * @var array<string>
     */
    protected array $generatedFiles = [];

    protected function setUpCleansGeneratedFiles(): void
    {
        $this->generatedFiles = [];
    }

    protected function tearDownCleansGeneratedFiles(): void
    {
        foreach ($this->generatedFiles as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }

        // Also clean up generated directories if empty
        $dirs = Collection::make($this->generatedFiles)
            ->map(fn (string $file) => dirname($file))
            ->unique()
            ->sort()
            ->reverse() // deepest first
            ->values();

        foreach ($dirs as $dir) {
            if (is_dir($dir) && $this->isDirEmpty($dir)) {
                rmdir($dir);
            }
        }

        $this->generatedFiles = [];
    }

    /**
     * Track a file to be cleaned up.
     */
    protected function trackFile(string $path): void
    {
        $this->generatedFiles[] = $path;
    }

    /**
     * Get the package root path.
     */
    protected function packageRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    /**
     * Get the source path.
     */
    protected function sourcePath(string $relative = ''): string
    {
        return $this->packageRoot().'/src/'.ltrim($relative, '/');
    }

    private function isDirEmpty(string $dir): bool
    {
        $files = scandir($dir);

        return $files === false || count(array_diff($files, ['.', '..'])) === 0;
    }
}
