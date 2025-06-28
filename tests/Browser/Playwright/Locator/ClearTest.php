<?php

declare(strict_types=1);

it('can clear input fields', function (): void {
    $page = page('/test/element-tests');
    $input = $page->getByTestId('prefilled-input');

    expect($input->inputValue())->not()->toBe('');

    $input->clear();

    expect($input->inputValue())->toBe('');
});
