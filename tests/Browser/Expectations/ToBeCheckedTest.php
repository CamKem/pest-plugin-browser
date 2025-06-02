<?php

declare(strict_types=1);

use PHPUnit\Framework\ExpectationFailedException;

it('passes when checkbox is checked', function (): void {
    $page = $this->page()->goto('/test/form-inputs');

    expect($page->locator('input[name="checked-checkbox"]'))->toBeChecked();
});

it('fails when checkbox is checked', function (): void {
    $page = $this->page()->goto('/test/form-inputs');

    expect($page->locator('input[name="checked-checkbox"]'))->not->toBeChecked();
})->throws(ExpectationFailedException::class);

it('passes when checkbox is not checked', function (): void {
    $page = $this->page()->goto('/test/form-inputs');

    expect($page->locator('input[name="unchecked-checkbox"]'))->not->toBeChecked();
});

it('fails when checkbox is not checked', function (): void {
    $page = $this->page()->goto('/test/form-inputs');

    expect($page->locator('input[name="unchecked-checkbox"]'))->toBeChecked();
})->throws(ExpectationFailedException::class);
