<?php

declare(strict_types=1);

use Tests\Helpers\CleansGeneratedFiles;

uses(CleansGeneratedFiles::class);

beforeEach(function (): void {
    $this->setUpCleansGeneratedFiles();
});

afterEach(function (): void {
    $this->tearDownCleansGeneratedFiles();
});

it('generates the cluster file in the correct location', function (): void {
    $this->artisan('make:filament-cluster', ['name' => 'TestCluster'])
        ->assertSuccessful();

    $path = $this->sourcePath('Filament/Clusters/TestCluster/TestClusterCluster.php');
    $this->trackFile($path);

    expect(file_exists($path))->toBeTrue();
})->skip('Panel commands require interactive prompt handling');
