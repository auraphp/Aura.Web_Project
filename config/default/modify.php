<?php
/**
 * @var $di The DI container.
 */
$router = $di->get('web_router');

$router->addValues(array(
    'request' => $di->get('web_request'),
    'response' => $di->get('web_response'),
));

$router->add('hello', '/')
    ->addValues(array(
        'controller' => function ($request, $response) {
            $response->content->set('Hello World!');
        }
    ));
