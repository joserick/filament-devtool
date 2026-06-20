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

it('generates the schema component file in the correct location', function (): void {
    $this->artisan('make:filament-schema-component', ['name' => 'TestComponent'])
        ->assertSuccessful();

    $path = $this->sourcePath('Filament/Schemas/Components/TestComponent.php');
    $this->trackFile($path);

    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    expect($content)->toContain('class TestComponent');
    expect($content)->toContain('namespace Joserick\Filament\DevTool\Filament\Schemas\Components');
});
