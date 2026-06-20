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

it('runs the boost skeleton command without error', function (): void {
    $this->artisan('devtool:boost-skeleton')
        ->assertSuccessful();
})->skip('Boost command requires TESTBENCH_WORKING_PATH constant and full skeleton setup');
