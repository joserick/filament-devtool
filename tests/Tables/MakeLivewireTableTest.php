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

it('generates the Livewire table file in the correct location', function (): void {
    $this->artisan('make:livewire-table', [
        'name' => 'TestLivewireTable',
        'model' => 'TestModel',
    ])
        ->expectsConfirmation('Should the table columns be generated from the current database columns?', 'no')
        ->assertSuccessful();

    $path = $this->sourcePath('Livewire/TestLivewireTable.php');
    $this->trackFile($path);

    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    expect($content)->toContain('class TestLivewireTable');
    expect($content)->toContain('namespace Joserick\Filament\DevTool\Livewire');
});
