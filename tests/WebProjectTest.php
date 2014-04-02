<?php
namespace Aura\Web_Project;

class WebProjectTest extends \PHPUnit_Framework_TestCase
{
    protected $descr = array(
        0 => array("pipe", "r"), // stdin
        1 => array("pipe", "w"), // stdout
        2 => array("pipe", "w")  // stderr
    );

    protected $pipes;

    protected $server;

    public function setUp()
    {
        $docroot = dirname(__DIR__) . '/web/';
        $this->server = proc_open(
            "php -S localhost:8080 -t {$docroot}",
            $this->descr,
            $this->pipes
        );
    }

    protected function tearDown()
    {
        proc_terminate($this->server);
    }

    public function test()
    {
        $actual = file_get_contents('http://localhost:8080/');
        $expect = 'Hello World!';
        $this->assertSame($expect, $actual);
    }
}
