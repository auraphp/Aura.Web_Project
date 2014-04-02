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

[Aura.Web_Kernel]: https://github.com/auraphp/Aura.Web_Kernel

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

Configuration files are located in `{$PROJECT_PATH}/config` folder.

The config mode can be set by `$_ENV['AURA_CONFIG_MODE']`, either via a server
variable or the `_env.php` file in the config directory.

By default the web project support 3 modes of configuration. 

1. dev  => for Development
2. test => for Testing
3. prod => for Production

The `Common.php` file is always loaded. If the mode is something different
then the appropriate file will be loaded after `common`.

Every configuration file should extend the `Aura\Di\Config`.

1. In the `define` method we can set the constructor parameters, 
setter methods, and shared services through the DI container.

2. The DI container is locked, and all 
these are for retrieving services from the DI container for
programmatic modification.

Additionally if you need another mode, say `staging` you want to edit
the `composer.json` and add where the configuration file should 
be looked up in the namespace.

```json
    "autoload": {        
        "psr-4": {
            "Vendor\\Package\\_Config\\": "config/"
        }
    },
    "extra": {
        "aura": {
            "type": "project",
            "config": {
                "common": "Vendor\\Package\\_Config\\Common",
                // ... more stuffs
                "staging": "Vendor\\Package\\_Config\\Staging"
            }
        }
    }
```

Don't forget to run `composer update` for composer to make the 
necessary changes to autoload.

Example
=======

```php
<?php
namespace Vendor\Package\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Dev extends Config
{
    public function define(Container $di)
    {  
        $di->set('db', $di->lazyNew('Aura\Sql\ExtendedPdo'));
        $di->params['Aura\Sql\ExtendedPdo']['dsn'] = 'mysql:host=localhost;dbname=test';
        $di->params['Aura\Sql\ExtendedPdo']['username'] = 'username';
        $di->params['Aura\Sql\ExtendedPdo']['password'] = 'password';
        $di->params['Aura\Sql\ExtendedPdo']['driver_options'] = array();
    }

    public function modify(Container $di)
    {
    }
}
```

### Routing and Dispatching

In the `modify` method we can add the routes and how it should be dispacthed. 
Here are three different styles of routing and dispatching.

#### Micro-Framework Style

The following is an example of a micro-framework style route, where the
controller logic is embedded in the route params. If the route 
needs to be on every mode edit the `config/Common.php`. If you only need
it in `dev` mode then in `config/Dev.php`

```php
<?php
/**
 * {$PROJECT_PATH}/config/Common.php
 */
namespace Vendor\Package\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {        
    }

    public function modify(Container $di)
    {
        $request  = $di->get('web_request');
        $response = $di->get('web_response');
        $router = $di->get('web_router');
        $router->add('blog.read', '/blog/read/{id}')
            ->addValues(array(
                'controller' => function ($id) use ($request, $response) {
                    $content = "Reading blog post $id";
                    $response->content->set(htmlspecialchars(
                        $content, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'
                    ));
                }
            ));
    }
}
```

You can now start up the built-in PHP server to get the application
running ...

    cd {$PROJECT_PATH}
    php -S localhost:8000 -t web/

... and browse to <http://localhost:8000/blog/read/88> to see the application
output.


#### Modified Micro-Framework Style

You can modify the above to put the controller logic in the dispatcher instead
of the route itself.

First, extract the logic to the dispatcher under the name `blog.read` 
and point the controller to the dispatcher.

```php
<?php
namespace Vendor\Package\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {        
    }

    public function modify(Container $di)
    {
        $request  = $di->get('web_request');
        $response = $di->get('web_response');
        
        // dispatcher
        $dispatcher = $di->get('web_dispatcher');
        $dispatcher->setObject('blog.read', function ($id) use ($request, $response) {
            $content = "Reading blog post $id";
            $response->content->set(htmlspecialchars(
                $content, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'
            ));
        });
        
        // router
        $router = $di->get('web_router');
        $router->add('blog.read', '/blog/read/{id}')
            ->addValues(array(
                'controller' => 'blog.read',
            ));        
    }
}
```

You can now start up the built-in PHP server to get the application
running ...

    cd {$PROJECT_PATH}
    php -S localhost:8000 -t web/

... and browse to <http://localhost:8000/blog/read/88> to see the application
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

Next, tell the project how to build the _BlogController_ through the DI
system. Edit the project `config/Common.php` config file to tell the
DI system to pass _Request_ and _Response_ objects to the constructor.

```php
<?php
/**
 * {$PROJECT_PATH}/config/Common.php
 */
namespace Vendor\Package\_Config;
 
use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {        
    }

    public function modify(Container $di)
    {
        $di->params['App\Controllers\BlogController'] = array(
            'request' => $di->lazyGet('web_request'),
            'response' => $di->lazyGet('web_response'),
        );
    }
}
```

After that, put the _App\Controllers\BlogController_ object in the dispatcher
under the name `blog` as a lazy-loaded instantiation ...

```php
<?php
/**
 * {$PROJECT_PATH}/config/Common.php
 */
namespace Vendor\Package\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {        
    }

    public function modify(Container $di)
    {
        $di->params['App\Controllers\BlogController'] = array(
            'request' => $di->lazyGet('web_request'),
            'response' => $di->lazyGet('web_response'),
        );
        $dispatcher = $di->get('web_dispatcher');
        $dispatcher->setObject('blog', $di->lazyNew('App\Controllers\BlogController'));
    }
}
```

... and finally, point the router to the `blog` controller object and its
its `read` action:

```php
<?php
/**
 * {$PROJECT_PATH}/config/Common.php
 */
namespace Vendor\Package\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {        
    }

    public function modify(Container $di)
    {
        $di->params['App\Controllers\BlogController'] = array(
            'request' => $di->lazyGet('web_request'),
            'response' => $di->lazyGet('web_response'),
        );
        $dispatcher = $di->get('web_dispatcher');
        $dispatcher->setObject('blog', $di->lazyNew('App\Controllers\BlogController'));

        $router = $di->get('web_router');
        $router->add('blog.read', '/blog/read/{id}')
            ->addValues(array(
                'controller' => 'blog',
                'action' => 'read',
            ));
    }
}
```

?>
```

You can now start up the built-in PHP server to get the application
running ...

    cd {$PROJECT_PATH}
    php -S localhost:8000 -t web/

... then browse to <http://localhost:8000/blog/read/88> to see the application
output.

#### Other Variations

These are only some common variations of router and dispatcher interactions;
[there are many other combinations][].

[there are many other combinations]: https://github.com/auraphp/Aura.Dispatcher/tree/develop-2#refactoring-to-architecture-changes

### Logging

The project automatically logs to `{$PROJECT_PATH}/tmp/{$mode}.log`. If
you want to change the logging behaviors, edit the
`config/Common.php` file to modify how Monolog handles entries.
