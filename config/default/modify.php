<?php
/**
 * @var Aura\Di\Container $di The DI container.
 */
$router     = $di->get('web_router');
$dispatcher = $di->get('web_dispatcher');
$request    = $di->get('web_request');
$response   = $di->get('web_response');

$router->add('hello', '/')
    ->addValues(array(
        'controller' => function () use ($request, $response) {
            $response->content->set('Hello World!');
        }
    ));
