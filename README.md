# Aura.Web_Project

Unlike Aura libraries, this Project package has dependencies. It composes
various libraries and an [Aura.Web_Kernel][] into a minimal framework for
web applications.

By "minimal" we mean *very* minimal. The project provides only a dependency
injection container, a configuration system, a router, a dispatcher, a pair of
request and response objects, and a logging instance.

This minimal implementation should not be taken as "restrictive". The DI
container, coupled with the kernel's two-stage configuration, allows a wide
range of programmatic service definitions. The router and dispatcher are built
with iterative refactoring in mind, so you can start with micro-framework-like
closure controllers, and work your way into more complex controller objects of
your own design.

## Foreword

TBD

## Getting Started

Install via Composer to a `{$PROJECT_PATH}` of your choosing:

    composer create-project --stability=dev aura/web-project {$PROJECT_PATH}
    
This will create the project skeleton and install all of the necessary
packages.

Once you have installed the project, start the built-in PHP server inside the
new project web directory:

    cd {$PROJECT_PATH}
    php -S localhost:8000 -t web/

When you browse to <http://localhost:8000> you should see "Hello World!" as
the output.

### Configuration

Configuration files are located in `{$PROJECT_PATH}/config` and are organized
into subdirectories by config mode.  (The config mode is stored in
`_mode` file in the config directory.)

The `default` mode directory is always loaded; if the mode is something other
than `default` then the files in that directory will be loaded after `default`.

Aura projects use a two-stage configuration system.

1. First, all `define.php` files are included from the packages and the
project; these define constructor parameters, setter methods, and shared
services through the DI container.

2. After that, the DI container is locked, and all `modify.php` files are
included; these are for retrieving services from the DI container for
programmatic modification.

(TBD: examples)


### Routing and Dispatching

To add routes of your own, edit the
`{$PROJECT_PATH}/config/default/modify.php` file. Therein, you will find some
service objects extracted from the DI container:

- `$router`, an [Aura\Router\Router][] instance,
- `$dispatcher`, an [Aura\Dispatcher\Dispatcher][] instance,
- `$request`, an [Aura\Web\Request][] instance, and
- `$response`, an [Aura\Web\Response][] instance.

[Aura\Router\Router]: https://github.com/auraphp/Aura.Router/tree/develop-2
[Aura\Dispatcher\Dispatcher]: https://github.com/auraphp/Aura.Dispatcher/tree/develop-2
[Aura\Web\Request]: https://github.com/auraphp/Aura.Web/tree/develop-2/README-REQUEST.md
[Aura\Web\Response]: https://github.com/auraphp/Aura.Web/tree/develop-2/README-RESPONSE.md


#### Micro-Framework Style

The following is an example of a micro-framework style route, where the
controller logic is embedded in the route params:

```php
<?php
/**
 * {$PROJECT_PATH}/config/default/define.php
 */
$router->add('blog.read', '/blog/read/{id}')
    ->addValues(array(
        'controller' => function ($id) use ($request, $response) {
            $content = "Reading blog post $id";
            $response->content->set(htmlspecialchars(
                $content, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'
            ));
        }
    ));
?>
```

You can modify this to put the controller logic in the dispatcher instead of
the route itself:

```php
<?php
/**
 * {$PROJECT_PATH}/config/default/define.php
 */
$router->add('blog.read', '/blog/read/{id}')
    ->addValues(array(
        'controller' => 'blog.read',
    ));

$dispatcher->setObject('blog.read', function ($id) use ($request, $response) {
    $content = "Reading blog post $id";
    $response->content->set(htmlspecialchars(
        $content, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'
    ));
});
?>
```

Either way, you can start up the built-in PHP server to get the application
running ...

    cd {$PROJECT_PATH}
    php -S localhost:8000 -t web/

... then browse to <http://localhost:8000/blog/read/88> to see the application
output.


#### Full-Stack Style

You can migrate from a micro-controller style to a full-stack style (or start
with full-stack style in the first place).

First, define a controller class and place it in the project `src/` directory.

```php
<?php
/**
 * {$PROJECT_PATH}/src/App/Controllers/BlogController.php
 */
namespace App\Controllers;

use Aura\Web\Request;
use Aura\Web\Response;

class BlogController
{
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    
    public function read($id)
    {
        $content = "Reading blog post $id";
        $this->response->content->set(htmlspecialchars(
            $content, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'
        ));
    }
}
?>
```

Next, now that we have a `BlogController` we need to tell the project how to
build it through the DI system. Edit the project `config/default/define.php` (first stage)
config file to tell the DI system to pass in the request and response objects to the
constructor.

```php
<?php
/**
 * {$PROJECT_PATH}/config/default/define.php
 */
$di->params['App\Controllers\BlogController'] = array(
    'request' => $di->lazyGet('web_request'),
    'response' => $di->lazyGet('web_response'),
);
?>
```

Finally, set the router and dispatcher to point to the `BlogController`. Do
this in the `config/default/modify.php` (second-stage) config file.

```php
<?php
/**
 * {$PROJECT_PATH}/config/default/modify.php
 */

// set the router to use the 'blog' controller (dispatcher) object ...
$router->add('blog.read', '/blog/read/{id}')
    ->addValues(array(
        'controller' => 'blog',
        'action' => 'read',
    ));

// .. and set the dispatcher 'blog' controller object to a new BlogController
$dispatcher->setObject('blog', $di->lazyNew('App\Controllers\BlogController'));
?>
```

Again, you can start up the built-in PHP server to get the application
running ...

    cd {$PROJECT_PATH}
    php -S localhost:8000 -t web/

... then browse to <http://localhost:8000/blog/read/88> to see the application
output.

### Other Variations

These are only some common variations of router and dispatcher interactions;
[there are many other combinations][].

[there are many other combinations]: https://github.com/auraphp/Aura.Dispatcher/tree/develop-2#refactoring-to-architecture-changes
