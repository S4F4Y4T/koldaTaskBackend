<?php

/**
 * Example Unit Test
 * 
 * This is a placeholder unit test to demonstrate the structure.
 * Unit tests should test individual units of code in isolation.
 * 
 * To run this test:
 * php artisan test tests/Unit/ExampleTest.php
 */

test('example unit test', function () {
    expect(true)->toBeTrue();
});

test('basic math operations', function () {
    expect(1 + 1)->toBe(2);
    expect(5 * 2)->toBe(10);
});

test('string operations', function () {
    expect('Laravel')->toBeString();
    expect(strlen('test'))->toBe(4);
});
