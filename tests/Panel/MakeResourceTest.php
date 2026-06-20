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

it('generates the resource with all expected files', function (): void {
    $this->artisan('make:filament-resource', ['model' => 'TestModel'])
        ->assertSuccessful();

    $paths = [
        $this->sourcePath('Filament/Resources/TestModelResource.php'),
        $this->sourcePath('Filament/Resources/Schemas/TestModelForm.php'),
        $this->sourcePath('Filament/Resources/Tables/TestModelsTable.php'),
    ];

    foreach ($paths as $path) {
        $this->trackFile($path);
    }

    foreach ($paths as $path) {
        expect(file_exists($path))->toBeTrue("Expected file {$path} to exist");
    }
})->skip('Panel commands require interactive prompt handling');
