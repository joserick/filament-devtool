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

it('generates the page file in the correct location', function (): void {
    $this->artisan('make:filament-page', ['name' => 'TestPage'])
        ->assertSuccessful();

    $path = $this->sourcePath('Filament/Pages/TestPage.php');
    $this->trackFile($path);

    expect(file_exists($path))->toBeTrue();
})->skip('Panel commands require interactive prompt handling');
