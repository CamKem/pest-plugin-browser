<?php

declare(strict_types=1);

test('navigates forward', function (): void {
    $this->visit(playground()->url())
        ->clickLink('Interacting with Elements')
        ->assertUrlIs(playground()->url('/test/interacting-with-elements'))
        ->back()
        ->assertUrlIs(playground()->url())
        ->forward()
        ->assertUrlIs(playground()->url('/test/interacting-with-elements'));
});
