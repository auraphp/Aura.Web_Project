# Aura.Web_Project

Unlike Aura libraries, this Project package has dependencies. It composes
various libraries and an [Aura.Web_Kernel][] into a minimal framework for
web applications.

By "minimal" we mean *very* minimal. The project provides only a dependency
injection container, a configuration system, a router, a dispatcher, request
and response objects, and a logging instance.

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
    php -S localhost:8000 web/

When you browse to <http://localhost:8000> you should see "Hello World!" as
the output.

### Configuration

Configuration files are located in `{$PROJECT_PATH}/config` and are organized
into subdirectories by operational mode.  (The operational mode is stored in
`_mode` file in the config directory.)

The `default` mode directory is always loaded; if the mode is something other
than `default` then the files in that directory will be loaded after `default`.

Aura projects use a two-stage configuration system.  First, all `define.php`
files are included from the packages and the project; these define params,
setters, and services through the DI container. After that, the DI container
is locked, and all `modify.php` files are included; these are for retrieving
services from the DI container for programmatic modification.

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

The following is an example of a micro-controller style route, where the
controller logic is embedded in the route params:

```php
<?php
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

Finally, you can convert to a full-stack style route and dispatch like so:

```php
<?php
$router->add('blog.read', '/blog/read/{id}')
    ->addValues(array(
        'controller' => 'blog',
        'action' => 'read',
    ));

$dispatcher->setObject('blog', $di->lazyNew('Vendor\Package\Blog'));
?>
```

You can place your `Vendor\Package\Blog` class at
`{$PROJECT_PATH}/src/Vendor/Package/Blog.php`, and the dispatcher will call
its `read($id)` method automatically.

These are only some common variations of router and dispatcher interactions;
[there are many other combinations][].

[there are many other combinations]: https://github.com/auraphp/Aura.Dispatcher/tree/develop-2#refactoring-to-architecture-changes
