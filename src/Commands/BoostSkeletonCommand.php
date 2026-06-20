<?php

namespace Joserick\Filament\DevTool\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('devtool:boost-skeleton')]
#[Description('Set up the Testbench skeleton for package development, merging composer dependencies and registering path repositories.')]
class BoostSkeletonCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (! defined('TESTBENCH_WORKING_PATH')) {
            return;
        }

        $this->createSkeletonSymlink();
        $this->mergeComposerDependencies();
        $this->registerPathRepository();
        $this->createSelfVendorSymlink();
        $this->fixServeScript();
    }

    /**
     * Create a symlink from skeleton/ to the Testbench Laravel skeleton,
     * providing the package with access to the full Laravel directory structure.
     */
    private function createSkeletonSymlink(): void
    {
        $skeletonPath = TESTBENCH_WORKING_PATH.'/skeleton';

        if (file_exists($skeletonPath)) {
            $this->info('skeleton/ symlink already exists. No action needed.');

            return;
        }

        $this->info('Creating skeleton/ symlink to vendor/orchestra/testbench-core/laravel...');
        symlink('vendor/orchestra/testbench-core/laravel', $skeletonPath);
        $this->info('Symlink created successfully.');
    }

    /**
     * Merge the package's composer.json dependencies into the skeleton's
     * composer.json when they are missing, and register the package itself
     * so Laravel Boost can discover its skills during boost:install.
     */
    private function mergeComposerDependencies(): void
    {
        $workingComposerPath = TESTBENCH_WORKING_PATH.'/composer.json';
        $skeletonComposerPath = TESTBENCH_WORKING_PATH.'/skeleton/composer.json';

        if (! file_exists($workingComposerPath) || ! file_exists($skeletonComposerPath)) {
            $this->error('composer.json not found. Ensure both the package and skeleton directories contain a composer.json file.');

            return;
        }

        $skeletonComposer = $this->readJsonFile($skeletonComposerPath);
        $packageComposer = $this->readJsonFile($workingComposerPath);
        $changed = false;

        // Merge missing dependency sections from the package into the skeleton
        if (! $this->hasAllDependencyKeys($skeletonComposer)) {
            $this->info('Merging package dependencies into skeleton/composer.json...');

            $skeletonComposer['require'] ??= $packageComposer['require'] ?? [];
            $skeletonComposer['require-dev'] ??= $packageComposer['require-dev'] ?? [];

            $changed = true;
        }

        // Register the package itself so Laravel Boost can discover its skills
        if ($this->registerSelfInSkeleton($packageComposer, $skeletonComposer)) {
            $changed = true;
        }

        if ($changed) {
            $this->writeJsonFile($skeletonComposerPath, $skeletonComposer);
            $this->info('skeleton/composer.json updated successfully.');
        } else {
            $this->info('skeleton/composer.json already up to date. No changes needed.');
        }
    }

    /**
     * Ensure the package itself is listed in the skeleton's require section
     * so Laravel Boost can discover its skills during boost:install.
     */
    private function registerSelfInSkeleton(array $packageComposer, array &$skeletonComposer): bool
    {
        $packageName = $packageComposer['name'] ?? null;

        if (! $packageName) {
            return false;
        }

        if (isset($skeletonComposer['require'][$packageName])) {
            return false;
        }

        $version = $packageComposer['version'] ?? '*';

        $skeletonComposer['require'] ??= [];
        $skeletonComposer['require'][$packageName] = $version;

        $this->info("Registered {$packageName}:{$version} in skeleton/composer.json for Laravel Boost discovery.");

        return true;
    }

    /**
     * Add a Composer path repository to the skeleton's composer.json pointing
     * back to the package root, so composer install/update can resolve the
     * package even though it's not published to a registry.
     */
    private function registerPathRepository(): void
    {
        $skeletonComposerPath = TESTBENCH_WORKING_PATH.'/skeleton/composer.json';

        if (! file_exists($skeletonComposerPath)) {
            return;
        }

        $skeletonComposer = $this->readJsonFile($skeletonComposerPath);

        // Check if a path repository for this package already exists
        foreach ($skeletonComposer['repositories'] ?? [] as $repository) {
            if (($repository['type'] ?? null) === 'path'
                && ($repository['url'] ?? null) === TESTBENCH_WORKING_PATH
            ) {
                $this->info('Path repository already registered in skeleton/composer.json.');

                return;
            }
        }

        $this->info('Registering path repository in skeleton/composer.json...');

        $skeletonComposer['repositories'] ??= [];
        $skeletonComposer['repositories'][] = [
            'type' => 'path',
            'url' => TESTBENCH_WORKING_PATH,
            'options' => [
                'symlink' => true,
            ],
        ];

        $this->writeJsonFile($skeletonComposerPath, $skeletonComposer);

        $this->info('Path repository registered successfully.');
    }

    /**
     * Create a symlink at vendor/joserick/watadata pointing to the package
     * root, so Composer and Laravel Boost can resolve the package path
     * without requiring a full composer install in the skeleton.
     *
     * If a future composer update removes this symlink, it will be recreated
     * on the next container start, and the path repository ensures composer
     * install can restore it as well.
     */
    private function createSelfVendorSymlink(): void
    {
        $packageName = $this->readJsonFile(TESTBENCH_WORKING_PATH.'/composer.json')['name'] ?? null;

        if (! $packageName) {
            return;
        }

        $vendorTargetDir = TESTBENCH_WORKING_PATH.'/vendor/'.$packageName;

        if (file_exists($vendorTargetDir)) {
            $this->info("vendor/{$packageName} already exists. No symlink needed.");

            return;
        }

        // Ensure the parent directory exists (e.g. vendor/joserick)
        $parentDir = dirname($vendorTargetDir);

        if (! is_dir($parentDir)) {
            mkdir($parentDir, 0755, true);
        }

        $this->info("Creating vendor/{$packageName} symlink to package root...");
        symlink(TESTBENCH_WORKING_PATH, $vendorTargetDir);
        $this->info('Symlink created successfully.');
    }

    /**
     * Check whether the composer array already contains both 'require'
     * and 'require-dev' keys.
     */
    private function hasAllDependencyKeys(array $composer): bool
    {
        return isset($composer['require'], $composer['require-dev']);
    }

    /**
     * Read and decode a JSON file into an associative array.
     */
    private function readJsonFile(string $path): array
    {
        return json_decode(file_get_contents($path), true);
    }

    /**
     * Encode and write an array to a JSON file with pretty-print formatting.
     */
    private function writeJsonFile(string $path, array $data): void
    {
        file_put_contents(
            $path,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * Ensure the serve script in composer.json includes --host and --port flags.
     *
     * The workbench:devtool command (called by workbench:install) overwrites
     * the serve script without --host=0.0.0.0 --port=80, which breaks the
     * Docker container's ability to serve on the correct interface.
     */
    private function fixServeScript(): void
    {
        if (! defined('TESTBENCH_WORKING_PATH')) {
            return;
        }

        $composerPath = TESTBENCH_WORKING_PATH.'/composer.json';

        if (! file_exists($composerPath)) {
            $this->error('composer.json not found.');

            return;
        }

        $composer = json_decode(file_get_contents($composerPath), true);

        if (! isset($composer['scripts']['serve'])) {
            return;
        }

        $updated = false;

        foreach ($composer['scripts']['serve'] as &$cmd) {
            if (str_contains($cmd, 'testbench serve') && ! str_contains($cmd, '--host')) {
                $cmd = str_replace('serve --ansi', 'serve --host=0.0.0.0 --port=80 --ansi', $cmd);
                $updated = true;
            }
        }
        unset($cmd);

        if ($updated) {
            file_put_contents(
                $composerPath,
                json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n"
            );

            $this->info('Fixed serve script in composer.json (added --host=0.0.0.0 --port=80).');
        }
    }
}
