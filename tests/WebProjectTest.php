<?php
namespace Aura\Web_Project;

class WebProjectTest extends \PHPUnit_Framework_TestCase
{
    public function testWeb()
    {
        $host = "127.0.0.1:8080";
        
        // $root = dirname(__DIR__) . '/web/';
        // $spec = array(
        //     0 => array("pipe", "r"), // stdin
        //     1 => array("pipe", "w"), // stdout
        //     2 => array("pipe", "w")  // stderr
        // );

        // $proc = proc_open(
        //     "php -S {$host} -t {$root}",
        //     $spec,
        //     $pipes
        // );

        $actual = file_get_contents("http://{$host}/");
        $expect = 'Hello World!';
        $this->assertSame($expect, $actual);

        // proc_terminate($proc);
    }
}
