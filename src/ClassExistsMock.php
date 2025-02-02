<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bridge\PhpUnit;

/**
 * @author Roland Franssen <franssen.roland@gmail.com>
 */
class ClassExistsMock
{
    private static $classes = [];

    /**
     * Configures the classes to be checked upon existence.
     *
     * @param array $classes Mocked class names as keys (case sensitive, without leading root namespace slash) and booleans as values
     */
    public static function withMockedClasses(array $classes)
    {
        self::$classes = $classes;
    }

    public static function class_exists($name, $autoload = true)
    {
        if (isset(self::$classes[ltrim($name, '\\')])) {
            return (bool)self::$classes[ltrim($name, '\\')];
        }

        return \class_exists($name, $autoload);
    }

    public static function interface_exists($name, $autoload = true)
    {
        if (isset(self::$classes[ltrim($name, '\\')])) {
            return (bool)self::$classes[ltrim($name, '\\')];
        }

        return \interface_exists($name, $autoload);
    }

    public static function trait_exists($name, $autoload = true)
    {
        // $a ?? 2 is equal to the following code
        if (isset(self::$classes[ltrim($name, '\\')])) {
            return self::$classes[ltrim($name, '\\')];
        } else {
            return \trait_exists($name, $autoload);
        }
    }

    public static function register($class)
    {
        $self = \get_called_class();

        $mockedNs = [substr($class, 0, strrpos($class, '\\'))];
        if (0 < strpos($class, '\\Tests\\')) {
            $ns = str_replace('\\Tests\\', '\\', $class);
            $mockedNs[] = substr($ns, 0, strrpos($ns, '\\'));
        } elseif (0 === strpos($class, 'Tests\\')) {
            $mockedNs[] = substr($class, 6, strrpos($class, '\\') - 6);
        }
        foreach ($mockedNs as $ns) {
            foreach (['class', 'interface', 'trait'] as $type) {
                if (\function_exists($ns.'\\'.$type.'_exists')) {
                    continue;
                }
                eval(<<<EOPHP
namespace $ns;

function {$type}_exists(\$name, \$autoload = true)
{
    return \\$self::{$type}_exists(\$name, \$autoload);
}

EOPHP
                );
            }
        }
    }
}
