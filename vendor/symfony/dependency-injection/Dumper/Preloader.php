<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ECSPrefix20210516\Symfony\Component\DependencyInjection\Dumper;

/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
final class Preloader
{
    /**
     * @return void
     * @param string $file
     */
    public static function append($file, array $list)
    {
        $file = (string) $file;
        if (!\file_exists($file)) {
            throw new \LogicException(\sprintf('File "%s" does not exist.', $file));
        }
        $cacheDir = \dirname($file);
        $classes = [];
        foreach ($list as $item) {
            if (0 === \strpos($item, $cacheDir)) {
                \file_put_contents($file, \sprintf("require_once __DIR__.%s;\n", \var_export(\strtr(\substr($item, \strlen($cacheDir)), \DIRECTORY_SEPARATOR, '/'), \true)), \FILE_APPEND);
                continue;
            }
            $classes[] = \sprintf("\$classes[] = %s;\n", \var_export($item, \true));
        }
        \file_put_contents($file, \sprintf("\n\$classes = [];\n%sPreloader::preload(\$classes);\n", \implode('', $classes)), \FILE_APPEND);
    }
    /**
     * @return void
     */
    public static function preload(array $classes)
    {
        \set_error_handler(function ($t, $m, $f, $l) {
            if (\error_reporting() & $t) {
                if (__FILE__ !== $f) {
                    throw new \ErrorException($m, 0, $t, $f, $l);
                }
                throw new \ReflectionException($m);
            }
        });
        $prev = [];
        $preloaded = [];
        try {
            while ($prev !== $classes) {
                $prev = $classes;
                foreach ($classes as $c) {
                    if (!isset($preloaded[$c])) {
                        self::doPreload($c, $preloaded);
                    }
                }
                $classes = \array_merge(\get_declared_classes(), \get_declared_interfaces(), \get_declared_traits());
            }
        } finally {
            \restore_error_handler();
        }
    }
    /**
     * @return void
     * @param string $class
     */
    private static function doPreload($class, array &$preloaded)
    {
        $class = (string) $class;
        if (isset($preloaded[$class]) || \in_array($class, ['self', 'static', 'parent'], \true)) {
            return;
        }
        $preloaded[$class] = \true;
        try {
            $r = new \ReflectionClass($class);
            if ($r->isInternal()) {
                return;
            }
            $r->getConstants();
            $r->getDefaultProperties();
            if (\PHP_VERSION_ID >= 70400) {
                foreach ($r->getProperties(\ReflectionProperty::IS_PUBLIC) as $p) {
                    self::preloadType($p->getType(), $preloaded);
                }
            }
            foreach ($r->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
                foreach ($m->getParameters() as $p) {
                    if ($p->isDefaultValueAvailable() && $p->isDefaultValueConstant()) {
                        $c = $p->getDefaultValueConstantName();
                        if ($i = \strpos($c, '::')) {
                            self::doPreload(\substr($c, 0, $i), $preloaded);
                        }
                    }
                    self::preloadType($p->getType(), $preloaded);
                }
                self::preloadType($m->getReturnType(), $preloaded);
            }
        } catch (\Throwable $e) {
            // ignore missing classes
        }
    }
    /**
     * @param \ReflectionType|null $t
     * @return void
     */
    private static function preloadType($t, array &$preloaded)
    {
        if (!$t) {
            return;
        }
        foreach ($t instanceof \ReflectionUnionType ? $t->getTypes() : [$t] as $t) {
            if (!$t->isBuiltin()) {
                self::doPreload($t instanceof \ReflectionNamedType ? $t->getName() : $t, $preloaded);
            }
        }
    }
}
