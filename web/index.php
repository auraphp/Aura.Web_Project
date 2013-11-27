<?php
use Aura\Web_Kernel\WebKernelFactory;

// the project base directory
$base = dirname(__DIR__);

// the project config mode
$file = str_replace("/", DIRECTORY_SEPARATOR, "{$base}/config/_mode");
$mode = trim(file_get_contents($file));
if (! $mode) {
    $mode = "default";
}

// autoloader
$loader = require "{$base}/vendor/autoload.php";

// create and invoke the project kernel
$factory = new WebKernelFactory;
$kernel = $factory->newInstance($base, $mode, $loader);
$kernel->__invoke();
