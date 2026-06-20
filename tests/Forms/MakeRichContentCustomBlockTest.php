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

it('generates the rich content custom block file in the correct location', function (): void {
    $this->artisan('make:rich-content-custom-block', ['name' => 'TestBlock'])
        ->assertSuccessful();

    $path = $this->sourcePath('Filament/Forms/Components/RichEditor/RichContentCustomBlocks/TestBlock.php');
    $this->trackFile($path);

    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    expect($content)->toContain('class TestBlock');
    expect($content)->toContain('namespace Joserick\Filament\DevTool\Filament\Forms\Components\RichEditor\RichContentCustomBlocks');
});
