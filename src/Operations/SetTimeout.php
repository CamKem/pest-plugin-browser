<?php

declare(strict_types=1);

namespace Pest\Browser\Operations;

use Pest\Browser\Contracts\Operation;

/**
 * @internal
 */
final readonly class SetTimeout implements Operation
{
    /**
     * Creates an operation instance.
     */
    public function __construct(
        private int $milliseconds
    ) {
        //
    }

    /**
     * Compile the operation.
     */
    public function compile(): string
    {
        return sprintf('test.setTimeout(%d);', $this->milliseconds);
    }
}
