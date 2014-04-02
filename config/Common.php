<?php
namespace Aura\Web_Project\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        $di->set('logger', $di->lazyNew('Monolog\Logger'));
    }

    public function modify(Container $di)
    {
        $this->modifyLogger($di);
        $this->modifyWebRouter($di);
        $this->modifyWebDispatcher($di);
    }

    public function modifyLogger(Container $di)
    {
        $project = $di->get('project');
        $mode = $project->getMode();
        $file = $project->getPath("tmp/log/{$mode}.log");

        $logger = $di->get('logger');
        $logger->pushHandler($di->newInstance(
            'Monolog\Handler\StreamHandler',
            array(
                'stream' => $file,
           )
        ));
    }

    public function modifyWebRouter(Container $di)
    {
        $router = $di->get('web_router');

        $router->add('hello', '/')
               ->setValues(array('controller' => 'hello'));
    }

    public function modifyWebDispatcher($di)
    {
        $dispatcher = $di->get('web_dispatcher');
        
        $dispatcher->setObject('hello', function () use ($di) {
            $response = $di->get('web_response');
            $response->content->set('Hello World!');
        });
    }
}
