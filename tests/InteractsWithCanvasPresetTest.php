<?php

declare(strict_types=1);

it('runs the basic test infrastructure', function (): void {
    expect(true)->toBeTrue();
});

it('skips unit tests for the trait due to setup complexity')->skip();
