<?php

namespace Drew\DebugStatementsFixers;

use PhpCsFixer\AbstractFunctionReferenceFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Tokens;

/**
 * @author Andrew Kovalyov <andrew.kovalyoff@gmail.com>
 */
final class Dump extends AbstractFunctionReferenceFixer
{
    /**
     * @var array
     */
    private $functions = array('dump', 'var_dump', 'dd');

    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isTokenKindFound(T_STRING);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'RemoveDebugStatements/dump';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Removes dump/var_dump statements, which shouldn\'t be in production ever.',
            array(new CodeSample("<?php\nvar_dump(false);\n")),
            null,
            'Risky when functions ' . implode(', ', $this->functions) . ' are redefined.'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens): void
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
