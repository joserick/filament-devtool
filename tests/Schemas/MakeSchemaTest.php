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

it('generates the schema file in the correct location', function (): void {
    $this->artisan('make:filament-schema', ['name' => 'TestSchema'])
        ->assertSuccessful();

    $path = $this->sourcePath('Filament/Schemas/TestSchema.php');
    $this->trackFile($path);

    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    expect($content)->toContain('class TestSchema');
    expect($content)->toContain('namespace Joserick\Filament\DevTool\Filament\Schemas');
});
