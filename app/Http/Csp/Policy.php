<?php

declare(strict_types=1);

namespace WTG\Http\Csp;

use Spatie\Csp\Directive;
use Spatie\Csp\Exceptions\InvalidDirective;
use Spatie\Csp\Exceptions\InvalidValueSet;
use Spatie\Csp\Keyword;

/**
 * CSP Policy.
 *
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Policy extends \Spatie\Csp\Policies\Policy
{
    /**
     * Configure the policy.
     *
     * @return void
     * @throws InvalidDirective
     * @throws InvalidValueSet
     */
    public function configure(): void
    {
        $this
            ->addDirective(Directive::DEFAULT, Keyword::NONE)
            ->addDirective(Directive::FRAME, 'https://www.google.com')
            ->addDirective(Directive::MANIFEST, KEYWORD::SELF)
            ->addDirective(
                Directive::SCRIPT,
                [
                    Keyword::UNSAFE_INLINE,
                    Keyword::UNSAFE_EVAL,
                    Keyword::SELF,
                    'https://browser.sentry-cdn.com',
                    'https://*.fontawesome.com',
                ]
            )
            ->addDirective(
                Directive::STYLE,
                [
                    Keyword::UNSAFE_INLINE,
                    Keyword::SELF,
                    'https://fonts.googleapis.com',
                    'https://cdn.jsdelivr.net',
                ]
            )
            ->addDirective(
                Directive::FONT,
                [
                    'data:',
                    'https://cdn.jsdelivr.net',
                    'https://fonts.gstatic.com',
                ]
            )
            ->addDirective(
                Directive::IMG,
                [
                    Keyword::SELF,
                    'data:',
                ]
            )
            ->addDirective(
                Directive::CONNECT,
                [
                    Keyword::SELF,
                    'https://*.fontawesome.com',
                ]
            );
    }
}
