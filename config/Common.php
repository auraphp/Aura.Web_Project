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
        $this->modifyRouter($di);
        $this->modifyDispatcher($di);
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

    public function modifyRouter(Container $di)
    {
        $request = $di->get('web_request');
        $response = $di->get('web_response');
        $router = $di->get('web_router');

        // example route for 'hello world' using request and response services
        $router->add('hello', '/')
            ->addValues(array(
                'controller' => function () use ($request, $response) {
                    $response->content->set('Hello World!');
                }
            ));
    }

    public function modifyDispatcher($di)
    {
        $dispatcher = $di->get('web_dispatcher');
    }
}
