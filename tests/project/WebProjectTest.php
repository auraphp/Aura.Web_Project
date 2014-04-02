<?php
namespace Aura\Web_Project;

class WebProjectTest extends \PHPUnit_Framework_TestCase
{
    protected static $server;

    protected static $host;

    public static function setUpBeforeClass()
    {
        static::$host = $_ENV['AURA_PROJECT_SERVER_HOST'];

        $start_server = isset($_ENV['AURA_PROJECT_START_SERVER'])
                      ? $_ENV['AURA_PROJECT_START_SERVER']
                      : true;

        if ($start_server) {
            static::startServer();
        }
    }

    public static function startServer()
    {
        $host = static::$host;
        $root = dirname(dirname(__DIR__)) . '/web/';
        $spec = array(
            0 => array("pipe", "r"), // stdin
            1 => array("pipe", "w"), // stdout
            2 => array("pipe", "w")  // stderr
        );

        static::$server = proc_open(
            "php -S {static::$host} -t {$root}",
            $spec,
            $pipes
        );
    }

    public static function tearDownAfterClass()
    {
        if (static::$server) {
            proc_terminate(static::$server);
        }
    }

    public function testWeb()
    {
        $host = static::$host;
        $actual = file_get_contents("http://{$host}/");
        $expect = 'Hello World!';
        $this->assertSame($expect, $actual);
    }
}
