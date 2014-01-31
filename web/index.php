<?php
/**
 * 
 * This file is part of Aura for PHP.
 * 
 * @package Aura.Web_Project
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */

// invoke the project kernel script
require dirname(__DIR__) . '/vendor/aura/project-kernel/scripts/kernel.php';

// create and invoke a web kernel
$web_kernel = $di->newInstance('Aura\Web_Kernel\WebKernel');
$web_kernel();
