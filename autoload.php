<?php

function autoload(string $path):void
{
    spl_autoload_register(function ($className) use ($path) {
        $nsPathNormalized = trim($className, '\\');
        $relativePath = str_replace('\\', '/', $nsPathNormalized) . '.php';

        $path = "$path/$relativePath";

        if (file_exists($path)) {
            require_once($path);
        }
    });
}