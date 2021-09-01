<?php

namespace Drew\RemoveDebugStatements\Tests;

use Drew\DebugStatementsFixers\Dump;
use PhpCsFixer\AbstractFixer;

/**
 * @author Andrew Kovalyov <andrew.kovalyoff@gmail.com>
 *
 * @internal
 */
final class DumpTest extends AbstractFixerTestCase
{
    /**
     * @dataProvider provideFixCases
     * @param mixed $expected
     * @param null|mixed $input
     */
    public function testFix($expected, $input = null)
    {
        $this->doTest($expected, $input);
    }

    public function createFixer(): AbstractFixer
    {
        return new Dump();
    }

    public function provideFixCases(): array
    {
        return [
            ['<?php echo "This should not be changed";'],
            [
                '<?php echo "This should be changed";',
                '<?php echo "This should be changed"; var_dump(true);',
            ],
            [
                '
<?php 
$a = 1;
$b = 1;
',

                '
<?php 
$a = 1;
var_dump($a);
$b = 1;
dump($b);
',
            ],
            [
                '<?php
            if(1 === 1) {
            }
            else {
                echo "The world has changed";
            }
            ',
                '<?php
            if(1 === 1) {
                dump(true);
            }
            else {
                echo "The world has changed";
                var_dump(false);
            }
            ',
            ],
            [
                '<?php
            class Dump{}
            new Dump();
            '
            ],
            [
                '<?php
            class dump{}
            new dump();
            ',
            ],
            [
                '<?php
            class dump{ public function dump(){} }
            new dump();
            '
            ],
            [
                '<?php
            class dump{ function dump(){} }
            new dump();
            ',
            ],
            [
                '<?php
                echo "The world has changed";
            ',
                '<?php
                echo "The world has changed";dd($_SERVER);
            ',
            ],
        ];
    }
}
