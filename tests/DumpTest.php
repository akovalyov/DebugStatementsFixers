<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Drew\RemoveDebugStatements\Tests;

use Drew\DebugStatementsFixers\Dump;
use PhpCsFixer\Test\AbstractFixerTestCase;

/**
 * @author Andrew Kovalyov <andrew.kovalyoff@gmail.com>
 *
 * @internal
 */
final class DumpTest extends AbstractFixerTestCase
{
    /**
     * @dataProvider provideFixCases
     */
    public function testFix($expected, $input = null)
    {
        $this->doTest($expected, $input);
    }

    function createFixer()
    {
        return new Dump();
    }

    public function provideFixCases()
    {
        return array(
            array('<?php echo "This should not be changed";'),
            array(
                '<?php echo "This should be changed";',
                '<?php echo "This should be changed"; var_dump(true);',
            ),
            array(
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
            ),
            array(
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
            ),
        );
    }
}
