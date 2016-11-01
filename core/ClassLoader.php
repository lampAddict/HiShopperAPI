<?php

namespace core;

class ClassLoader {

    public static function load($classname) {
        if (!preg_match('/(?<namespace>.+)\\\\(?<class>[^\\\\]+)$/', $classname, $matches)) {
            //throw new \Exception("Can not load class $classname.");
            return;
        }

        $path = __DIR__ . '/../' . str_replace('\\', '/', $matches['namespace']) . '/' . $matches['class'] . '.php';

        if (!is_file($path)) return;

        if ($path) require_once($path);
    }

}

spl_autoload_register(__NAMESPACE__ . '\ClassLoader::load');