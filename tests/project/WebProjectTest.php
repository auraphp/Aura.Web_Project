<?php
namespace Aura\Web_Project;

class WebProjectTest extends \PHPUnit_Framework_TestCase
{
    protected $host;

    public function setUp()
    {
        $this->host = $_ENV['AURA_PROJECT_SERVER_HOST'];
    }

    public function tearDown()
    {
        if ($this->server) {
            proc_close($this->server);
        }
    }

    public function testWeb()
    {
        $url = "http://{$this->host}/index.php";
        $actual = file_get_contents($url);
        $expect = 'Hello World!';
        $this->assertSame($expect, $actual);
    }
}
