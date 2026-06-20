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

it('generates the Livewire form file in the correct location', function (): void {
    $this->artisan('make:livewire-form', ['name' => 'TestLivewireForm'])
        ->expectsConfirmation('Would you like to create a form for a model?', 'no')
        ->assertSuccessful();

    $path = $this->sourcePath('Livewire/TestLivewireForm.php');
    $this->trackFile($path);

    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    expect($content)->toContain('class TestLivewireForm');
    expect($content)->toContain('namespace Joserick\Filament\DevTool\Livewire');
});
