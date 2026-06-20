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

it('generates the Livewire schema file in the correct location', function (): void {
    $this->artisan('make:livewire-schema', ['name' => 'TestLivewireSchema'])
        ->assertSuccessful();

    $path = $this->sourcePath('Livewire/TestLivewireSchema.php');
    $this->trackFile($path);

    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    expect($content)->toContain('class TestLivewireSchema');
    expect($content)->toContain('namespace Joserick\Filament\DevTool\Livewire');
});
