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

it('generates the infolist entry file in the correct location', function (): void {
    $this->artisan('make:infolist-entry', ['name' => 'TestEntry'])
        ->assertSuccessful();

    $path = $this->sourcePath('Filament/Infolists/Components/TestEntry.php');
    $this->trackFile($path);

    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    expect($content)->toContain('class TestEntry extends Entry');
    expect($content)->toContain('namespace Joserick\Filament\DevTool\Filament\Infolists\Components');
});
