<?php

declare(strict_types=1);

namespace Pest\Browser\Api;

use Pest\Browser\Execution;
use Pest\Browser\Playwright\Locator;
use Pest\Browser\Playwright\Page;
use Pest\Browser\Support\GuessLocator;

final readonly class Webpage
{
    use Concerns\InteractsWithElements,
        Concerns\InteractsWithTab,
        Concerns\MakesConsoleAssertions,
        Concerns\MakesElementAssertions,
        Concerns\MakesUrlAssertions;

    /**
     * The page instance.
     */
    public function __construct(
        private Page $page,
    ) {
        //
    }

    /**
     * Dumps the current page's content and stops the execution.
     */
    public function dd(): never
    {
        dd($this->page->content());
    }

    /**
     * Gets the page's content.
     */
    public function content(): string
    {
        return $this->page->content();
    }

    /**
     * Gets the page's URL.
     */
    public function url(): string
    {
        return $this->page->url();
    }

    /**
     * Performs a screenshot of the current page and saves it to the given path.
     */
    public function screenshot(?string $name = null): self
    {
        $name = is_string($name) ? $name : date('Y_m_d_H_i_s_u');

        $this->page->screenshot($name);

        return $this;
    }

    /**
     * Submits the first form found on the page.
     */
    public function submit(): self
    {
        $this->guessLocator('[type="submit"]')->click();

        return $this;
    }

    /**
     * Executes a script in the context of the page.
     */
    public function script(string $content): mixed
    {
        return $this->page->evaluate($content);
    }

    /**
     * Gets the page instance.
     */
    public function value(string $selector): string
    {
        return $this->guessLocator($selector)->inputValue();
    }

    /**
     * Pause for the given number of seconds.
     */
    public function wait(int|float $seconds): self
    {
        Execution::instance()->wait($seconds);

        return $this;
    }

    /**
     * Gets the locator for the given selector.
     */
    private function guessLocator(string $selector, ?string $value = null): Locator
    {
        return (new GuessLocator($this->page))->for($selector, $value);
    }
}
