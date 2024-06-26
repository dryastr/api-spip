<a name="readme-top"></a>

<!-- PROJECT LOGO -->
<br />
<div align="center">
<h3 align="center">API SPIP</h3>

</div>
<br />
<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#usage">Documentation</a></li>
    <li><a href="#changelog">Changelog</a></li>
    <li><a href="#queue">Queue</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## About The Project

API SPIP

Domain:
* spip.com

<p align="right">(<a href="#readme-top">back to top</a>)</p>



### Built With

This service uses the PHP programming language and uses several libraries.

**Programming language:**
- <a href="https://www.php.net/">PHP</a> (8.2)
- <a href="https://nodejs.org/">Node JS</a> (18.6)

**Framework:**
- <a href="https://laravel.com/">Laravel</a> (10)

**Plugin/Library:**
- <a href="https://www.npmjs.com/package/husky">Husky</a> - Git Hook For Commit Lint
- <a href="https://www.npmjs.com/package/chokidar">Chokidar</a> - Hot Reload OpenSwoole
- <a href="https://repo.mifx.com/micro-services/packages/mifx-core">mifx-package/mifx-core</a> - Package Internal MIFX
- <a href="https://github.com/laravel/octane">laravel/octane</a> - Octane(Swoole Server Library)
- <a href="https://github.com/doctrine/dbal">doctrine/dbal</a> - Powerful PHP database abstraction layer (DBAL) with many features for database schema introspection and management
- <a href="https://github.com/PHP-Open-Source-Saver/jwt-auth">php-open-source-saver/jwt-auth</a> - PHP Open Source - JWT Auth (Generate & Validate JWT)
- <a href="https://github.com/predis/predis">predis/predis</a> - A flexible and feature-complete Redis client for PHP
- <a href="https://github.com/spatie/laravel-ignition">spatie/laravel-ignition</a> - A beautiful error page for Laravel applications.
- <a href="https://github.com/symfony/http-kernel">symfony/http-kernel</a> - Provides a structured process for converting a Request into a Response
- <a href="https://github.com/vyuldashev/laravel-queue-rabbitmq">vladimir-yuldashev/laravel-queue-rabbitmq</a> - RabbitMq (Message Broker)

**Plugin/Library development only:**
- <a href="https://github.com/pestphp/pest">pestphp/pest</a> - Pest (Unit Testing)
- <a href="https://github.com/barryvdh/laravel-ide-helper">barryvdh/laravel-ide-helper</a> - Laravel IDE Helper, generates correct PHPDocs for all Facade classes, to improve auto-completion.
- <a href="https://github.com/laravel/pint">laravel/pint</a> - An opinionated code formatter for PHP.
- <a href="https://github.com/laravel/sail">laravel/sail</a> - Docker files for running a basic Laravel application.


<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- GETTING STARTED -->
## Getting Started

This is an example of how you may give instructions on setting up your project locally.
To get a local copy up and running follow these simple example steps.

### Prerequisites

This is an example of how to list things you need to use the software and how to install them.
* npm
  ```sh
  npm install npm@18.6 -g
  ```
* swoole
  ``` sh
  pecl install openswoole
  ```
* docker
    - https://docs.docker.com/desktop/install/mac-install/
* composer
    - https://getcomposer.org/download/
* xdebug
    - https://xdebug.org/docs/install/#pecl


### Installation
#### With Docker
1. Clone the project
    ```bash
    git clone 
    ```

2. Go to the project directory
    ```bash
    cd api-{service-name}
    ```
3. Create database MYSQL `spip`
4. Setup .env at <a href="">env {service-name}</a>

5. Install app and dependencies
    ```bash
    composer install
    ```
     ```bash
    npm install
    ```
6. Migration & Seed
    ```bash
    php artisan migrate --seed
    ```

7. Build with docker
    ```bash
    docker-compose build
    ```

8. Start the server
    ```bash
    docker-compose up
    ```

9. You can now access the server at http://localhost:8012/

10. You can now access the Clockwork server at http://localhost:8012/clockwork


#### Without Docker
1. Clone the project
    ```bash
    git clone https://repo.mifx.com/micro-services/api-{service-name}
    ```

2. Go to the project directory
    ```bash
    cd api-{service-name}
    ```
3. Create database MYSQL `spip`
4. Setup .env at <a href="">env {service-name}</a>

5. Install app and dependencies
    ```bash
    composer install
    ```
    ```bash
    npm install
    ```
6. Migration & Seed
    ```bash
    php artisan migrate --seed
    ```
7. Start the server
    ```bash
    php artisan octane:start --watch --server=swoole
    ```

8. You can now access the server at http://localhost:8000/

<p align="right">(<a href="#readme-top">back to top</a>)</p>

### Running Tests

1. Create database PostgreSQL `spip_testing`

2. Setup .env.testing at <a href="">env {service-name}</a>

3. Run tests
    ```bash
    sh test.sh
    ```


<!-- USAGE EXAMPLES -->
## Usage

You can now access the server with docker at http://localhost:8080. <br />
You can now access the server without docker at http://localhost:8000.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- DOCUMENTATIONS -->
## Documentation
You can study some documentation related to this service
- <a href="#">Coming soon</a>

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- Changelog -->
## Changelog

<a href="./docs/changelog.md">Changelog</a>

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- Queue -->
## Queue


<a href="./docs/queue.md">Queue Documentation</a>

<p align="right">(<a href="#readme-top">back to top</a>)</p>

## List Route

<a href="./docs/route.md">Queue Documentation</a>

<p align="right">(<a href="#readme-top">back to top</a>)</p>

#   a p i - s p i p  
 