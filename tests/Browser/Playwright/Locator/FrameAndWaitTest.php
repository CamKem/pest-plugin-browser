<?php

declare(strict_types=1);

use Pest\Browser\Playwright\Locator;

it('can create frame locator', function (): void {
    $page = $this->page()->goto('/test/element-tests');
    $frameElement = $page->getByTestId('test-frame');

    $frameLocator = $frameElement->frameLocator('.frame-content');

    expect($frameLocator)->toBeInstanceOf(Locator::class);
});

it('can get page identifier from locator', function (): void {
    $page = $this->page()->goto('/test/element-tests');
    $button = $page->getByTestId('click-button');

    $locatorPageId = $button->page();

    expect($locatorPageId)->toBeString();
    expect($locatorPageId)->not()->toBeEmpty();
});

it('can wait for element state', function (): void {
    $page = $this->page()->goto('/test/element-tests');
    $button = $page->getByTestId('click-button');

    $button->waitForState('visible');

    expect($button->isVisible())->toBeTrue();
});

it('can wait for enabled state', function (): void {
    $page = $this->page()->goto('/test/element-tests');
    $button = $page->getByTestId('click-button');

    $button->waitForState('enabled');

    expect($button->isEnabled())->toBeTrue();
});

it('can wait with timeout', function (): void {
    $page = $this->page()->goto('/test/element-tests');
    $button = $page->getByTestId('click-button');

    $button->waitForState('visible', ['timeout' => 5000]);

    expect($button->isVisible())->toBeTrue();
});

it('can wait for element state using waitForElementState method', function (): void {
    $page = $this->page()->goto('/test/element-tests');
    $button = $page->getByTestId('click-button');

    $button->waitForElementState('visible');

    expect($button->isVisible())->toBeTrue();
});

it('can wait for element state with options using waitForElementState', function (): void {
    $page = $this->page()->goto('/test/element-tests');
    $button = $page->getByTestId('click-button');

    $button->waitForElementState('enabled', ['timeout' => 5000]);

    expect($button->isEnabled())->toBeTrue();
});

it('can wait for selector to appear relative to locator', function (): void {
    $page = $this->page()->goto('/test/element-tests');
    $container = $page->getByTestId('profile-section');

    $childLocator = $container->waitForSelector('h2');

    expect($childLocator)->toBeInstanceOf(Locator::class);
});

it('returns null when waitForSelector times out', function (): void {
    $page = $this->page()->goto('/test/element-tests');
    $container = $page->getByTestId('profile-section');

    $result = $container->waitForSelector('.definitely-non-existent-element-12345', ['timeout' => 100]);

    expect($result)->toBeNull();
});
