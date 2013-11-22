<?php
use Aura\Di\Config;
use Aura\Di\Container;
use Aura\Di\Forge;
use Aura\Web_Kernel\WebKernel;

// the project base directory
$base = dirname(__DIR__);

// autoloader
$loader = require "{$base}/vendor/autoload.php";

// DI container
$di = new Container(new Forge(new Config));

// config mode
$file = str_replace("/", DIRECTORY_SEPARATOR, "{$base}/config/_mode");
$mode = trim(file_get_contents($file));
if (! $mode) {
    $mode = "default";
}

// create and invoke the project kernel
$kernel = new WebKernel($loader, $di, $base, $mode);
$kernel->__invoke();
