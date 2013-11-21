# Aura.Web_Project

Unlike Aura libraries, this Project package has dependencies. It composes
various libraries and an [Aura.Web_Kernel][] into a minimal framework for
web applications.

## Foreword

TBD

## Getting Started

Install via Composer to a {$PROJECT_PATH} of your choosing:

    composer create-project --stability=dev aura/web-project {$PROJECT_PATH}
    
This will create the project skeleton and install all of the necessary
packages.

Once you have installed the Aura system, start the built-in PHP server inside
the new project web directory:

    cd {$PROJECT_PATH}/web
    php -S localhost:8000

When you browse to <http://localhost:8000> you should see "Hello World!" as
the output.

## Configuration

TBD

## Routing

TBD

## Controllers

TBD

[Aura.Web_Kernel]: https://github.com/auraphp/Aura.Web_Kernel
[Aura.Di]: https://github.com/auraphp/Aura.Di
