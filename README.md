# Aura.Web_Project

Unlike Aura libraries, this Project package has dependencies. It composes
various libraries and an [Aura.Web_Kernel][] into a minimal framework for
web applications.

By "minimal" we mean *very* minimal. The project provides only a 
[dependency injection container][], a configuration system, a 
[router][], a [dispatcher][], [request][] and [response][] objects, 
and a [logging][] instance. This is the bare minimum needed
to get a web application running.

This minimal implementation should not be taken as "restrictive". The DI
container, coupled with the kernel's two-stage configuration, allows a wide
range of programmatic service definitions. The router and dispatcher are built
with iterative refactoring in mind, so you can start with micro-framework-like
closure controllers, and work your way into more complex controller objects of
your own design.

## Foreword

TBD

## Getting Started

Install via [Composer][] to a {$PROJECT_PATH} of your choosing:

    composer create-project --stability=dev aura/web-project {$PROJECT_PATH}
    
This will create the project skeleton and install all of the necessary
packages.

Once you have installed the project, start the built-in PHP server inside the
new project web directory:

    cd {$PROJECT_PATH}/web
    php -S localhost:8000

When you browse to <http://localhost:8000> you should see "Hello World!" as
the output.

### Configuration

TBD

### Routing

We can define the routes in `config/{mode}/modify.php`.

> {mode} can be `default`, `production`, `testing`, `development` which are
> defined in `config/_mode`

Get the router instance via

```php
$router   = $di->get('web_router');
```

Now add routes as 

```php
$router->add('hello', '/')
    ->addValues(array(
        'controller' => function () use ($request, $response) {
            $response->content->set('Hello World!');
        }
    ));
```

Read more about adding routes from [Aura.Router](https://github.com/auraphp/Aura.Router/tree/develop-2#adding-a-route)

#### REST routes

```php
$router->attachResource('blog', '/blog');

$dispatcher->setObject('blog', function () use ($request, $response) {
    return new Simple\Controller\Blog($request, $response);
});
```

When you add a REST route, you need to define all the 
[actions mentioned here](https://github.com/auraphp/Aura.Router/tree/develop-2#attaching-rest-resource-routes)

### Controllers

For autoloading the library add

```
    // otherdefinitions
    "autoload": {
        "psr-0": { "Simple": "lib/" }
    }
```

to `composer.json` file.

> Assume the controller is at `lib/Simple/Controller/Blog.php`.

```php
<?php
namespace Simple\Controller;

class Blog
{    
    public function __construct($request, $response)
    {
        $this->request  = $request;
        $this->response = $response;
    }

    /**
     * 
     * GET
     * 
     * browse the resources, optionally in a format.
     * can double for search when a query string is passed.
     * 
     */
    public function browse()
    {
        $this->sendSomething();
    }
    
    /**
     * 
     * GET
     * 
     * get a single resource by ID, optionally in a format
     * 
     */
    public function read()
    {
        return $this->sendSomething();
    }
    
    /**
     * 
     * GET
     * 
     * get the form to add new resource
     * 
     */
    public function add()
    {
        return $this->sendSomething();
    }
    
    /**
     * 
     * GET
     * 
     * get the form for an existing resource by ID, optionally in a format
     * 
     */
    public function edit()
    {
        return $this->sendSomething();
    }
    
    /**
     * 
     * delete a resource by ID
     * 
     */
    public function delete()
    {
        return $this->sendSomething();
    }
    
    /**
     * 
     * POST
     * 
     * create a resource and get back its location
     * 
     */
    public function create()
    {
        return $this->sendSomething();
    }
    
    /**
     * 
     * PATCH
     * 
     * update part or all an existing resource by ID
     * 
     */
    public function update()
    {
        return $this->sendSomething();
    }
    
    /**
     * 
     * PUT
     * 
     * replace an existing resource by ID
     *
     */
    public function replace()
    {
        return $this->sendSomething();
    }
    
    protected function sendSomething()
    {
        return $this->response->content->set(
            $this->request->method->get() . ' '
            . $this->request->url->get(PHP_URL_PATH) . PHP_EOL . PHP_EOL
            . 'Params: ' . var_export($this->request->params->get(), true)
            . PHP_EOL
        );
    }
}
```
> Point to <http://localhost:8000/blog>

With the help `Aura\Dispatcher\InvokeMethodTrait` the action name can be altered.

An example to call `actionBrowse` than simple `browse` method.

```php
<?php
namespace Simple\Controller;

use Aura\Dispatcher\InvokeMethodTrait;

class Blog
{
    use InvokeMethodTrait;
    
    public function __construct($request, $response)
    {
        $this->request  = $request;
        $this->response = $response;
    }

    public function __invoke()
    {
        $action = isset($this->request->params['action']) ? $this->request->params['action'] : 'index';
        $method = 'action' . ucfirst($action);
        return $this->invokeMethod($this, $method, $this->request->params);
    }
    
    /**
     * 
     * GET
     * 
     * browse the resources, optionally in a format.
     * can double for search when a query string is passed.
     * 
     */
    public function actionBrowse()
    {
        $this->sendSomething();
    }
    
    /**
     * 
     * GET
     * 
     * get a single resource by ID, optionally in a format
     * 
     */
    public function actionRead()
    {
        return $this->sendSomething();
    }
    
    /**
     * 
     * GET
     * 
     * get the form to add new resource
     * 
     */
    public function actionAdd()
    {
        return $this->sendSomething();
    }
    
    /**
     * 
     * GET
     * 
     * get the form for an existing resource by ID, optionally in a format
     * 
     */
    public function actionEdit()
    {
        return $this->sendSomething();
    }
    
    /**
     * 
     * delete a resource by ID
     * 
     */
    public function actionDelete()
    {
        return $this->sendSomething();
    }
    
    /**
     * 
     * POST
     * 
     * create a resource and get back its location
     * 
     */
    public function actionCreate()
    {
        return $this->sendSomething();
    }
    
    /**
     * 
     * PATCH
     * 
     * update part or all an existing resource by ID
     * 
     */
    public function actionUpdate()
    {
        return $this->sendSomething();
    }
    
    /**
     * 
     * PUT
     * 
     * replace an existing resource by ID
     *
     */
    public function actionReplace()
    {
        return $this->sendSomething();
    }
    
    protected function sendSomething()
    {
        return $this->response->content->set(
            $this->request->method->get() . ' '
            . $this->request->url->get(PHP_URL_PATH) . PHP_EOL . PHP_EOL
            . 'Params: ' . var_export($this->request->params->get(), true)
            . PHP_EOL
        );
    }
}

```

[Aura.Web_Kernel]: https://github.com/auraphp/Aura.Web_Kernel
[Aura.Di]: https://github.com/auraphp/Aura.Di
[dependency injection container]: https://github.com/auraphp/Aura.Di
[router]: https://github.com/auraphp/Aura.Router/tree/develop-2
[dispatcher]: https://github.com/auraphp/Aura.Dispatcher
[request]: https://github.com/auraphp/Aura.Web/blob/develop-2/README-REQUEST.md
[response]: https://github.com/auraphp/Aura.Web/blob/develop-2/README-RESPONSE.md
[Composer]: http://getcomposer.org/download/
