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

it('generates the exporter file in the correct location', function (): void {
    $this->artisan('make:filament-exporter', ['model' => 'TestModel'])
        ->expectsConfirmation('Should the exporter columns be generated from the current database columns?', 'no')
        ->assertSuccessful();

    $path = $this->sourcePath('Filament/Exports/TestModelExporter.php');
    $this->trackFile($path);

    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    expect($content)->toContain('class TestModelExporter');
    expect($content)->toContain('namespace Joserick\Filament\DevTool\Filament\Exports');
});
