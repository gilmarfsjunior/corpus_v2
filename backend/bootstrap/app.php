<?php

declare(strict_types=1);

spl_autoload_register(static function (string $className): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    if (strpos($className, $prefix) !== 0) {
        return;
    }

    $relativeClass = substr($className, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
