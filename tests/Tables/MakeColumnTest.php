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

it('generates the table column file in the correct location', function (): void {
    $this->artisan('make:table-column', ['name' => 'TestColumn'])
        ->expectsConfirmation('Do you want to embed the HTML of the view in the column class?', 'no')
        ->assertSuccessful();

    $path = $this->sourcePath('Filament/Tables/Columns/TestColumn.php');
    $this->trackFile($path);

    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    expect($content)->toContain('class TestColumn extends Column');
    expect($content)->toContain('namespace Joserick\Filament\DevTool\Filament\Tables\Columns');
});
