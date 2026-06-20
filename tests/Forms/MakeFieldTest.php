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

it('generates the form field file in the correct location', function (): void {
    $this->artisan('make:form-field', ['name' => 'TestField'])
        ->assertSuccessful();

    $path = $this->sourcePath('Filament/Forms/Components/TestField.php');
    $this->trackFile($path);

    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    expect($content)->toContain('class TestField extends Field');
    expect($content)->toContain('namespace Joserick\Filament\DevTool\Filament\Forms\Components');
});
