<?php

namespace Drew\DebugStatementsFixers;

use PhpCsFixer\AbstractFunctionReferenceFixer;
use PhpCsFixer\Tokenizer\Tokens;

/**
 * @author Andrew Kovalyov <andrew.kovalyoff@gmail.com>
 */
final class Dump extends AbstractFunctionReferenceFixer
{
    /**
     * @var array
     */
    private $functions = array('dump', 'var_dump');

    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, Tokens $tokens)
    {
        $end = $tokens->count() - 1;

        foreach ($this->functions as $function) {
            $currIndex = 0;
            while (null !== $currIndex) {
                $matches = $tokens->findSequence(array(array(T_STRING, $function), '('), $currIndex, $end, false);

                if (null === $matches) {
                    break;
                }
                $match = array_keys($matches);

                $funcStart = $tokens->getPrevNonWhitespace($match[0]);

                $funcEnd = $tokens->getNextTokenOfKind($match[1], array(';'));

                $tokens->clearRange($funcStart + 1, $funcEnd);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens)
    {
        return $tokens->isTokenKindFound(T_STRING);
    }

    /**
     * {@inheritdoc}
     */
    public function isRisky()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'RemoveDebugStatements/dump';
    }
}
