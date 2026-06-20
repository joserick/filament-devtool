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

it('generates the form schema file in the correct location', function (): void {
    $this->artisan('make:filament-form', ['name' => 'TestForm'])
        ->expectsConfirmation('Would you like to create a form for a model?', 'no')
        ->assertSuccessful();

    $path = $this->sourcePath('Filament/Schemas/TestForm.php');
    $this->trackFile($path);

    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    expect($content)->toContain('class TestForm');
    expect($content)->toContain('namespace Joserick\Filament\DevTool\Filament\Schemas');
});
