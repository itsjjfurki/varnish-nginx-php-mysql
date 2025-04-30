<?php

spl_autoload_register(function ($class) {
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    include $path . '.php';
});

if (PHP_SAPI === 'cli') {
    if(!isset($argv[1]) || $argv[1] !== 'setup')
    {
        return;
    }

    require_once __DIR__ . '/setup.php';
    return;
}

$initializedFile = __DIR__ . '/../.initialized';

if (!file_exists($initializedFile)) {
    echo '<h2>Database is being prepared. Please wait while the application installs.</h2>';
    return;
}

use App\Core\Routes;

$route = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

new Routes('/' . $route[1] ?? '/');
