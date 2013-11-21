# Aura.Web_Project

Unlike Aura libraries, this Project package has dependencies. It composes
various libraries and an [Aura.Web_Kernel][] into a minimal framework for
web applications.

By "minimal" we mean *very* minimal. The project provides only a dependency
injection container, a configuration system, a router, a dispatcher, request
and response objects, and a logging instance. This is the bare minimum needed
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

Install via Composer to a {$PROJECT_PATH} of your choosing:

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

TBD

### Controllers

TBD

[Aura.Web_Kernel]: https://github.com/auraphp/Aura.Web_Kernel
[Aura.Di]: https://github.com/auraphp/Aura.Di
