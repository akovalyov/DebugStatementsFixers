<?php

/*
 * This file is extracted from the test suite of PHP CS Fixer.
 */

namespace Drew\RemoveDebugStatements\Tests;

use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

/**
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * @internal
 */
trait AssertTokensTrait
{
    private function assertTokens(Tokens $expectedTokens, Tokens $inputTokens)
    {
        $option = ['JSON_PRETTY_PRINT'];

        foreach ($expectedTokens as $index => $expectedToken) {
            $inputToken = $inputTokens[$index];

            $this->assertTrue(
                $expectedToken->equals($inputToken),
                sprintf("The token at index %d must be:\n%s,\ngot:\n%s.", $index, $expectedToken->toJson($option), $inputToken->toJson($option))
            );

            $expectedTokenKind = $expectedToken->isArray() ? $expectedToken->getId() : $expectedToken->getContent();
            $this->assertTrue(
                $inputTokens->isTokenKindFound($expectedTokenKind),
                sprintf(
                    'The token kind %s (%s) must be found in tokens collection.',
                    $expectedTokenKind,
                    \is_string($expectedTokenKind) ? $expectedTokenKind : Token::getNameForId($expectedTokenKind)
                )
            );
        }

        $this->assertSame($expectedTokens->count(), $inputTokens->count(), 'Both collections must have the same length.');
    }
}
