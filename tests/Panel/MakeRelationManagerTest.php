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

it('generates the relation manager file inside the resource directory', function (): void {
    $this->artisan('make:filament-resource', ['model' => 'BlogPost'])
        ->assertSuccessful();

    $this->artisan('make:filament-relation-manager', [
        'resource' => 'BlogPost',
        'relationship' => 'comments',
    ])->assertSuccessful();

    $path = $this->sourcePath('Filament/Resources/BlogPosts/RelationManagers/CommentsRelationManager.php');
    $this->trackFile($path);

    expect(file_exists($path))->toBeTrue();
})->skip('Panel commands require interactive prompt handling');
