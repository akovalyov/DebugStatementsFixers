<?php

namespace Drew\DebugStatementsFixers;

use PhpCsFixer\AbstractFunctionReferenceFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
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
    public function isCandidate(Tokens $tokens)
    {
        return $tokens->isTokenKindFound(T_STRING);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'RemoveDebugStatements/dump';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition()
    {
        return new FixerDefinition(
            'Removes dump/var_dump statements, which shouldn\'t be in production ever.',
            array(new CodeSample("<?php\nvar_dump(false);"))
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens)
    {
        foreach ($this->functions as $function) {
            $currIndex = 0;
            while (null !== $currIndex) {
                $matches = $this->find($function, $tokens, $currIndex);

                if (null === $matches) {
                    break;
                }

                $funcStart = $tokens->getPrevNonWhitespace($matches[0]);

                if ($tokens[$funcStart]->isGivenKind(T_NEW) || $tokens[$funcStart]->isGivenKind(T_FUNCTION)) {
                    break;
                }

                $funcEnd = $tokens->getNextTokenOfKind($matches[1], array(';'));

                $tokens->clearRange($funcStart + 1, $funcEnd);
            }
        }
    }
}
