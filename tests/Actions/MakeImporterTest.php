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

it('generates the importer file in the correct location', function (): void {
    $this->artisan('make:filament-importer', ['model' => 'TestModel'])
        ->expectsChoice('How would you like the importer to behave?', 'create', [
            'create' => 'Create records only',
            'upsert' => 'Create a record if it does not exist, otherwise update it',
            'update' => 'Update records only',
        ])
        ->expectsConfirmation('Should the importer columns be generated from the current database columns?', 'no')
        ->assertSuccessful();

    $path = $this->sourcePath('Filament/Imports/TestModelImporter.php');
    $this->trackFile($path);

    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    expect($content)->toContain('class TestModelImporter');
    expect($content)->toContain('namespace Joserick\Filament\DevTool\Filament\Imports');
});
