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

it('generates the widget file in the correct location', function (): void {
    $this->artisan('make:filament-widget', ['name' => 'TestWidget', '--chart' => true])
        ->assertSuccessful();

    $path = $this->sourcePath('Filament/Widgets/TestWidget.php');
    $this->trackFile($path);

    expect(file_exists($path))->toBeTrue();
})->skip('Panel commands require interactive prompt handling');
