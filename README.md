# Debug Statements Fixers
Fixers set for PHP-CS-Fixer. Removes debug statements, which shouldn't be in production ever. 

![PHP 7.0](https://img.shields.io/badge/PHP-7.0-8C9CB6.svg?style=flat)
[![Build Status](https://travis-ci.org/akovalyov/DebugStatementsFixers.svg?branch=master)](https://travis-ci.org/akovalyov/DebugStatementsFixers)
[![Latest Stable Version](https://poser.pugx.org/akovalyov/DebugStatementsFixers/v/stable)](https://packagist.org/packages/akovalyov/DebugStatementsFixers)
[![Total Downloads](https://poser.pugx.org/akovalyov/DebugStatementsFixers/downloads)](https://packagist.org/packages/akovalyov/DebugStatementsFixers)
[![License](https://poser.pugx.org/akovalyov/DebugStatementsFixers/license)](https://packagist.org/packages/akovalyov/DebugStatementsFixers)
[![Coverage Status](https://coveralls.io/repos/github/akovalyov/DebugStatementsFixers/badge.svg?branch=master)](https://coveralls.io/github/akovalyov/DebugStatementsFixers?branch=master)

If you have ever had 500 Error on production because `dump` function is missing, 
or you forget to remove debug statements time to time - this small package is for you.

Debug statements are good for debugging, but it should never get to master branch.

Initially proposed as [RFC in PHP-CS-Fixer repository](https://github.com/FriendsOfPHP/PHP-CS-Fixer/issues/2195), 
it was considered too risky to have it in core (see discussion at https://github.com/FriendsOfPHP/PHP-CS-Fixer/pull/2218)

*N.B.* These fixers are risky and potentially can break your application. You should understand consequences of having it in your project (especially a legacy one). You are warned.


## Usage

1. Install it:
    
    ```bash
    $ composer require drew/debug-statements-fixers:^0.1 --dev
    ```

2. Adjust your PHP-CS-Fixer config:
    
    ```php
    # .php_cs.dist
    <?php
    
    $finder = PhpCsFixer\Finder::create()
        ->in([__DIR__.'/src', __DIR__.'/tests']);

    return PhpCsFixer\Config::create()
        ->setRules([
            //any fixers you would like to have
            'RemoveDebugStatements/dump' => true,
        ])
        ->registerCustomFixers([new Drew\DebugStatementsFixers\Dump()])
        ->setRiskyAllowed(true)
        ->setFinder($finder);    
    ```
3. Enjoy.

#### Protip!

Works best when integrated with your CI server, just add this step to your CI config:

```bash
$ php vendor/bin/php-cs-fixer fix --diff --dry-run -v
```
