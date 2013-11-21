<?php
// the project base directory
$base = dirname(__DIR__);

// autoloader
require "{$base}/vendor/autoload.php";

// config mode
$file = str_replace("/", DIRECTORY_SEPARATOR, "{$base}/config/_mode");
$mode = trim(file_get_contents($file));
if (! $mode) {
    $mode = "default";
}

// create and invoke the project kernel
$factory = new Aura\Web_Kernel\WebKernelFactory;
$kernel = $factory->newInstance($base, $mode);
$kernel->__invoke();
