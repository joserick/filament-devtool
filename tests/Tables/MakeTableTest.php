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

it('generates the table file in the correct location', function (): void {
    $this->artisan('make:filament-table', [
        'name' => 'TestTable',
        'model' => 'TestModel',
    ])
        ->expectsConfirmation('Should the table columns be generated from the current database columns?', 'no')
        ->assertSuccessful();

    $path = $this->sourcePath('Filament/Tables/TestTable.php');
    $this->trackFile($path);

    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    expect($content)->toContain('class TestTable');
    expect($content)->toContain('namespace Joserick\Filament\DevTool\Filament\Tables');
});
