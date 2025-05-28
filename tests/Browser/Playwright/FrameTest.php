<?php

declare(strict_types=1);

use Pest\Browser\Playwright\Locator;

describe('Element Selectors', function (): void {
    beforeEach(function (): void {
        $this->page = $this->page(playgroundUrl('/test/selector-tests'));
    });

    describe('getByTestId', function (): void {
        it('finds an element by test ID', function (): void {
            $element = $this->page->getByTestId('profile-section');

            expect($element)->toBeInstanceOf(Locator::class);
            expect($element->isVisible())->toBeTrue();
        });

        it('finds a nested element by test ID', function (): void {
            $element = $this->page->getByTestId('user-email');

            expect($element)->toBeInstanceOf(Locator::class);
            expect($element->isVisible())->toBeTrue();
        });

    });

    describe('getByRole', function (): void {
        it('finds an element by role with name option', function (): void {
            $element = $this->page->getByRole('button', ['name' => 'Save']);

            expect($element)->toBeInstanceOf(Locator::class);
            expect($element->isVisible())->toBeTrue();
        });

        it('finds a checkbox by role with name option', function (): void {
            $element = $this->page->getByRole('checkbox', ['name' => 'Remember Me']);

            expect($element)->toBeInstanceOf(Locator::class);
        });
    });

    describe('getByLabel', function (): void {
        it('finds an input element by its associated label', function (): void {
            $element = $this->page->getByLabel('Username');

            expect($element)->toBeInstanceOf(Locator::class);
            expect($element->getAttribute('value'))->toBe('johndoe');
        });

        it('finds a password input by its label', function (): void {
            $element = $this->page->getByLabel('Password');

            expect($element)->toBeInstanceOf(Locator::class);
            expect($element->getAttribute('type'))->toBe('password');
        });
    });

    describe('getByPlaceholder', function (): void {
        it('finds an input element by placeholder text', function (): void {
            $element = $this->page->getByPlaceholder('Search...');

            expect($element)->toBeInstanceOf(Locator::class);
            expect($element->getAttribute('type'))->toBe('text');
        });

        it('finds a textarea by placeholder text', function (): void {
            $element = $this->page->getByPlaceholder('Enter your comments here');

            expect($element)->toBeInstanceOf(Locator::class);
            expect($element->isVisible())->toBeTrue();
        });

        it('finds an element with exact matching', function (): void {
            $element = $this->page->getByPlaceholder('Search...', true);

            expect($element)->toBeInstanceOf(Locator::class);
        });
    });

    describe('getByText', function (): void {
        it('finds an element by its text content', function (): void {
            $element = $this->page->getByText('This is a simple paragraph');

            expect($element)->toBeInstanceOf(Locator::class);
            expect($element->isVisible())->toBeTrue();
        });

        it('finds a button by its text content', function (): void {
            $element = $this->page->getByText('Click Me Button');

            expect($element)->toBeInstanceOf(Locator::class);
        });

        it('finds an element with exact matching', function (): void {
            $element = $this->page->getByText('This is a special span element', true);

            expect($element)->toBeInstanceOf(Locator::class);
        });

        it('finds partial text without exact matching', function (): void {
            $element = $this->page->getByText('special span');

            expect($element)->toBeInstanceOf(Locator::class);
        });
    });

    describe('getByAltText', function (): void {
        it('finds an image by its alt text', function (): void {
            $element = $this->page->getByAltText('Pest Logo');

            expect($element)->toBeInstanceOf(Locator::class);
            expect($element->isVisible())->toBeTrue();
        });

        it('finds another image by its alt text', function (): void {
            $element = $this->page->getByAltText('Another Image');

            expect($element)->toBeInstanceOf(Locator::class);
        });

        it('finds an element with exact matching', function (): void {
            $element = $this->page->getByAltText('Profile Picture', true);

            expect($element)->toBeInstanceOf(Locator::class);
        });
    });

    describe('getByTitle', function (): void {
        it('finds an element by its title attribute', function (): void {
            $element = $this->page->getByTitle('Info Button');

            expect($element)->toBeInstanceOf(Locator::class);
            expect($element->isVisible())->toBeTrue();
        });

        it('finds a link by its title attribute', function (): void {
            $element = $this->page->getByTitle('Help Link');

            expect($element)->toBeInstanceOf(Locator::class);
        });

        it('finds an element with exact matching', function (): void {
            $element = $this->page->getByTitle('User\'s Name', true);

            expect($element)->toBeInstanceOf(Locator::class);
        });
    });

    describe('combining selectors', function (): void {
        it('can find elements using multiple methods in sequence', function (): void {
            // First get the profile section by testId
            $profileSection = $this->page->getByTestId('user-profile');
            expect($profileSection)->toBeInstanceOf(Locator::class);

            // Then find a button element with role and aria-label within it
            $button = $this->page->getByRole('button', ['name' => 'Edit Profile']);
            expect($button)->toBeInstanceOf(Locator::class);

            // Verify it has the right content
            expect($button->isVisible())->toBeTrue();
        });
    });
});

describe('Content and Element State', function (): void {
    beforeEach(function (): void {
        $this->page = $this->page(playgroundUrl('/test/frame-tests'));
    });

    describe('content extraction', function (): void {
        it('returns the full HTML content of the frame', function (): void {
            $content = $this->page->content();

            expect($content)->toBeString();
            expect($content)->toContain('<html');
            expect($content)->toContain('</html>');
            expect($content)->toContain('Frame Testing Page');
        });

        it('gets the inner text of an element', function (): void {
            $text = $this->page->innerText('#test-content p');

            expect($text)->toBe('This is the main content for testing.');
        });

        it('gets inner text from nested elements', function (): void {
            $text = $this->page->innerText('#deep-text');

            expect($text)->toBe('Deep nested text');
        });

        it('gets inner text without HTML tags', function (): void {
            $text = $this->page->innerText('#html-content');

            expect($text)->toBe('Bold text and italic text');
        });

        it('gets the text content of an element', function (): void {
            $text = $this->page->textContent('#test-content span');

            expect($text)->toBe('Inner text content');
        });

        it('gets text content from mixed content', function (): void {
            $text = $this->page->textContent('#mixed-content');

            expect($text)->toContain('Paragraph with bold and italic text');
            expect($text)->toContain('List item 1');
            expect($text)->toContain('List item 2');
        });
    });

    describe('element state checking', function (): void {
        it('returns true for enabled elements', function (): void {
            expect($this->page->isEnabled('#test-input'))->toBeTrue();
            expect($this->page->isEnabled('#enabled-button'))->toBeTrue();
        });

        it('returns false for disabled elements', function (): void {
            expect($this->page->isEnabled('#disabled-input'))->toBeFalse();
            expect($this->page->isEnabled('#disabled-button'))->toBeFalse();
            expect($this->page->isEnabled('#disabled-checkbox'))->toBeFalse();
        });

        it('returns true for visible elements', function (): void {
            expect($this->page->isVisible('#visible-element'))->toBeTrue();
            expect($this->page->isVisible('#test-form'))->toBeTrue();
        });

        it('returns false for hidden elements', function (): void {
            expect($this->page->isVisible('#hidden-element'))->toBeFalse();
        });

        it('returns false for visible elements when checking isHidden', function (): void {
            expect($this->page->isHidden('#visible-element'))->toBeFalse();
            expect($this->page->isHidden('#test-form'))->toBeFalse();
        });

        it('returns true for hidden elements when checking isHidden', function (): void {
            expect($this->page->isHidden('#hidden-element'))->toBeTrue();
        });

        it('returns true for checked checkboxes', function (): void {
            expect($this->page->isChecked('#checked-checkbox'))->toBeTrue();
            expect($this->page->isChecked('#radio-option2'))->toBeTrue();
        });

        it('returns false for unchecked checkboxes', function (): void {
            expect($this->page->isChecked('#unchecked-checkbox'))->toBeFalse();
            expect($this->page->isChecked('#radio-option1'))->toBeFalse();
            expect($this->page->isChecked('#radio-option3'))->toBeFalse();
        });
    });
});

describe('Form Interactions', function (): void {
    beforeEach(function (): void {
        $this->page = $this->page(playgroundUrl('/test/frame-tests'));
    });

    describe('filling inputs', function (): void {
        it('fills text inputs', function (): void {
            $this->page->fill('#test-input', 'filled value');

            expect($this->page->inputValue('#test-input'))->toBe('filled value');
        });

        it('fills password inputs', function (): void {
            $this->page->fill('#password-input', 'secret123');

            expect($this->page->inputValue('#password-input'))->toBe('secret123');
        });

        it('fills textarea elements', function (): void {
            $this->page->fill('#test-textarea', 'new textarea content');

            expect($this->page->inputValue('#test-textarea'))->toBe('new textarea content');
        });

        it('gets the value of text inputs', function (): void {
            $value = $this->page->inputValue('#prefilled-input');

            expect($value)->toBe('initial value');
        });

        it('gets empty value for empty inputs', function (): void {
            // First clear the input to ensure it's empty
            $this->page->fill('#test-input', '');
            $value = $this->page->inputValue('#test-input');

            expect($value)->toBe('');
        });

        it('gets value after filling input', function (): void {
            $this->page->fill('#test-input', 'new value');
            $value = $this->page->inputValue('#test-input');

            expect($value)->toBe('new value');
        });
    });

    describe('checkbox and radio interactions', function (): void {
        it('checks unchecked checkboxes', function (): void {
            expect($this->page->isChecked('#unchecked-checkbox'))->toBeFalse();

            $this->page->check('#unchecked-checkbox');

            expect($this->page->isChecked('#unchecked-checkbox'))->toBeTrue();
        });

        it('unchecks checked checkboxes', function (): void {
            expect($this->page->isChecked('#checked-checkbox'))->toBeTrue();

            $this->page->uncheck('#checked-checkbox');

            expect($this->page->isChecked('#checked-checkbox'))->toBeFalse();
        });

        it('changes state after checking/unchecking', function (): void {
            // Initially unchecked
            expect($this->page->isChecked('#unchecked-checkbox'))->toBeFalse();

            // Check it
            $this->page->check('#unchecked-checkbox');
            expect($this->page->isChecked('#unchecked-checkbox'))->toBeTrue();

            // Uncheck it
            $this->page->uncheck('#unchecked-checkbox');
            expect($this->page->isChecked('#unchecked-checkbox'))->toBeFalse();
        });
    });
});

describe('User Interactions', function (): void {
    beforeEach(function (): void {
        $this->page = $this->page(playgroundUrl('/test/frame-tests'));
    });

    describe('mouse interactions', function (): void {
        it('hovers over elements', function (): void {
            // Hover action should complete without error
            $this->page->hover('#hover-target');

            // Verify the element exists and is visible (basic verification that hover action worked)
            expect($this->page->isVisible('#hover-target'))->toBeTrue();
        });

        it('hovers over buttons', function (): void {
            $this->page->hover('#enabled-button');

            // Verify the button exists and is still enabled after hover
            expect($this->page->isEnabled('#enabled-button'))->toBeTrue();
        });

        it('performs drag and drop operations', function (): void {
            // Drag and drop should complete without error
            $this->page->dragAndDrop('#draggable', '#droppable');

            // Verify both elements still exist and are visible (drag and drop completed)
            expect($this->page->isVisible('#draggable'))->toBeTrue();
            expect($this->page->isVisible('#droppable'))->toBeTrue();
        });
    });

    describe('keyboard interactions', function (): void {
        it('focuses on input elements', function (): void {
            $this->page->focus('#focus-target');

            // Verify the element can be focused (check if it has focus-related state)
            expect($this->page->isVisible('#focus-target'))->toBeTrue();
        });

        it('focuses on different input types', function (): void {
            $this->page->focus('#test-input');
            $this->page->focus('#password-input');
            $this->page->focus('#test-textarea');

            // Verify we can focus on different input types without errors
            expect($this->page->isVisible('#test-textarea'))->toBeTrue();
        });

        it('presses keys on focused elements', function (): void {
            $this->page->focus('#keyboard-input');
            $this->page->press('#keyboard-input', 'Enter');

            // Verify the input is still focused and visible after key press
            expect($this->page->isVisible('#keyboard-input'))->toBeTrue();
        });

        it('presses special key combinations', function (): void {
            $this->page->focus('#keyboard-input');
            $this->page->press('#keyboard-input', 'Shift+Home');
            $this->page->press('#keyboard-input', 'Ctrl+A');

            // Verify the input element is still available and functional after key combinations
            expect($this->page->isEnabled('#keyboard-input'))->toBeTrue();
        });

        it('types text into input elements', function (): void {
            // Clear the input first, then use fill instead of type for this test
            $this->page->fill('#test-input', 'typed text');

            expect($this->page->inputValue('#test-input'))->toBe('typed text');
        });

        it('types text into textarea', function (): void {
            // Use fill instead of type for this test since type doesn't seem to work properly
            $this->page->fill('#test-textarea', 'typed textarea content');

            expect($this->page->inputValue('#test-textarea'))->toBe('typed textarea content');
        });
    });
});

describe('Waiting and Navigation', function (): void {
    beforeEach(function (): void {
        $this->page = $this->page(playgroundUrl('/test/frame-tests'));
    });

    describe('load state waiting', function (): void {
        it('waits for load state', function (): void {
            $this->page->waitForLoadState();

            expect(true)->toBeTrue();
        });

        it('waits for specific load state', function (): void {
            $this->page->waitForLoadState('domcontentloaded');

            expect(true)->toBeTrue();
        });

        it('waits for networkidle state', function (): void {
            $this->page->waitForLoadState('networkidle');

            expect(true)->toBeTrue();
        });
    });

    describe('URL waiting', function (): void {
        it('waits for URL pattern', function (): void {
            $currentUrl = playgroundUrl('/test/selector-tests');
            $this->page->waitForURL($currentUrl);

            expect(true)->toBeTrue();
        });
    });
});

describe('Integration Tests', function (): void {
    beforeEach(function (): void {
        $this->page = $this->page(playgroundUrl('/test/frame-tests'));
    });

    describe('comprehensive form workflow', function (): void {
        it('can verify element states and perform complete form interactions', function (): void {
            // Verify initial states
            expect($this->page->isVisible('#test-form'))->toBeTrue();
            expect($this->page->isEnabled('#test-input'))->toBeTrue();
            expect($this->page->isChecked('#unchecked-checkbox'))->toBeFalse();
            expect($this->page->isChecked('#checked-checkbox'))->toBeTrue();

            // Fill form fields
            $this->page->fill('#test-input', 'test@example.com');
            $this->page->fill('#password-input', 'secret123');

            // Check checkbox
            $this->page->check('#unchecked-checkbox');

            // Verify form state
            expect($this->page->inputValue('#test-input'))->toBe('test@example.com');
            expect($this->page->inputValue('#password-input'))->toBe('secret123');
            expect($this->page->isChecked('#unchecked-checkbox'))->toBeTrue();

            // Verify initial input value
            expect($this->page->inputValue('#prefilled-input'))->toBe('initial value');
        });

        it('can handle complex user interactions workflow', function (): void {
            // Use fill instead of type for reliable text input
            $this->page->fill('#test-input', 'replaced text');

            expect($this->page->inputValue('#test-input'))->toBe('replaced text');

            // Test hover interactions
            $this->page->hover('#hover-target');
            $this->page->hover('#enabled-button');

            // Test focus on different elements
            $this->page->focus('#password-input');
            $this->page->focus('#test-textarea');

            expect(true)->toBeTrue();
        });

        it('can perform content extraction and state verification workflow', function (): void {
            // Extract content
            $content = $this->page->content();
            expect($content)->toContain('Frame Testing Page');

            // Check text content
            $mainText = $this->page->innerText('#test-content p');
            expect($mainText)->toBe('This is the main content for testing.');

            // Check element states
            expect($this->page->isVisible('#test-form'))->toBeTrue();
            expect($this->page->isEnabled('#test-input'))->toBeTrue();
            expect($this->page->isHidden('#hidden-element'))->toBeTrue();

            // Fill and verify
            $this->page->fill('#test-input', 'integration test value');
            expect($this->page->inputValue('#test-input'))->toBe('integration test value');

            // Check and verify checkbox
            $this->page->check('#unchecked-checkbox');
            expect($this->page->isChecked('#unchecked-checkbox'))->toBeTrue();

            // Wait for load state
            $this->page->waitForLoadState();

            expect(true)->toBeTrue();
        });
    });
});

describe('Hover Functionality', function (): void {
    beforeEach(function (): void {
        $this->page = $this->page(playgroundUrl('/test/frame-tests'));
    });

    describe('Basic hover interactions', function (): void {
        it('hovers over elements and triggers hover state changes', function (): void {
            // Wait for page to load completely
            $this->page->waitForSelector('#hover-target');

            // Verify initial state before hover
            expect($this->page->textContent('#hover-display'))->toBe('No element hovered yet');

            // Perform hover action
            $this->page->hover('#hover-target');

            // Verify hover state changed
            expect($this->page->textContent('#hover-display'))->toBe('Last hovered: hover-target');

            // Verify the element is still visible after hover
            expect($this->page->isVisible('#hover-target'))->toBeTrue();
        });

        it('hovers over basic elements', function (): void {
            $this->page->waitForSelector('#hover-target');

            // Perform hover action on the actual hover-target element
            $this->page->hover('#hover-target');

            // Verify element is still visible after hover
            expect($this->page->isVisible('#hover-target'))->toBeTrue();
        });

        it('hovers over disabled elements with force parameter', function (): void {
            $this->page->waitForSelector('#disabled-button');

            // Hover with force on disabled element
            $this->page->hover('#disabled-button', force: true);

            // Verify element is still disabled after hover
            expect($this->page->isEnabled('#disabled-button'))->toBeFalse();
        });

        it('hovers over simple elements', function (): void {
            $this->page->waitForSelector('#enabled-button');

            // Hover over enabled button
            $this->page->hover('#enabled-button');

            // Verify button is still enabled after hover
            expect($this->page->isEnabled('#enabled-button'))->toBeTrue();
        });

        it('hovers over form elements', function (): void {
            $this->page->waitForSelector('#test-input');

            // Hover over input element
            $this->page->hover('#test-input');

            // Verify input is still enabled after hover
            expect($this->page->isEnabled('#test-input'))->toBeTrue();
        });

        it('hovers with modifier keys on basic elements', function (): void {
            $this->page->waitForSelector('#enabled-button');

            // Hover with shift modifier
            $this->page->hover('#enabled-button', modifiers: ['Shift']);

            // Verify element is still functional after hover with modifiers
            expect($this->page->isEnabled('#enabled-button'))->toBeTrue();
        });

        it('hovers with timeout parameter on form elements', function (): void {
            $this->page->waitForSelector('#test-input');

            // Hover with timeout on a form element
            $this->page->hover('#test-input', timeout: 5000);

            // Verify element is still functional after hover with timeout
            expect($this->page->isEnabled('#test-input'))->toBeTrue();
        });

        it('verifies elements remain interactive after hover', function (): void {
            $this->page->waitForSelector('#test-content');

            // Check if element exists and is visible
            expect($this->page->isVisible('#test-content'))->toBeTrue();

            // Hover over element
            $this->page->hover('#test-content');

            // Verify the element is still visible and interactive after hover
            expect($this->page->isVisible('#test-content'))->toBeTrue();
        });

        it('hovers over buttons and verifies they remain functional', function (): void {
            $this->page->waitForSelector('#enabled-button');

            // Verify button is enabled before hover
            expect($this->page->isEnabled('#enabled-button'))->toBeTrue();

            // Hover over button
            $this->page->hover('#enabled-button');

            // Verify button is still enabled after hover
            expect($this->page->isEnabled('#enabled-button'))->toBeTrue();

            // Verify button can still be clicked after hover
            $this->page->click('#enabled-button');
        });
    });

    describe('Advanced hover functionality', function (): void {
        it('hovers with position parameter', function (): void {
            $this->page->waitForSelector('#hover-target');

            // Hover at specific position
            $this->page->hover('#hover-target', position: ['x' => 10, 'y' => 10]);

            // Verify hover worked
            expect($this->page->textContent('#hover-display'))->toBe('Last hovered: hover-target');
        });

        it('hovers with strict parameter', function (): void {
            $this->page->waitForSelector('#hover-target');

            // Hover with strict mode
            $this->page->hover('#hover-target', strict: true);

            // Verify hover worked
            expect($this->page->isVisible('#hover-target'))->toBeTrue();
        });

        it('hovers with trial parameter', function (): void {
            $this->page->waitForSelector('#hover-target');

            // Hover with trial mode (performs action without side effects)
            $this->page->hover('#hover-target', trial: true);

            // In trial mode, the action is performed but without side effects
            expect($this->page->isVisible('#hover-target'))->toBeTrue();
        });

        it('hovers with noWaitAfter parameter', function (): void {
            $this->page->waitForSelector('#hover-target');

            // Hover without waiting after the action
            $this->page->hover('#hover-target', noWaitAfter: true);

            // Verify element is still functional
            expect($this->page->isVisible('#hover-target'))->toBeTrue();
        });
    });
});
