RAD Framework
==========================

[![Latest Stable Version](https://poser.pugx.org/rad/rad-framework/v/stable)](https://packagist.org/packages/rad/rad-framework)
[![Build Status](https://travis-ci.org/guillaumemonet/Rad.svg?branch=master)](https://travis-ci.org/guillaumemonet/Rad)
[![Total Downloads](https://poser.pugx.org/rad/rad-framework/downloads)](https://packagist.org/packages/rad/rad-framework)
[![Latest Unstable Version](https://poser.pugx.org/rad/rad-framework/v/unstable)](https://packagist.org/packages/rad/rad-framework)
[![License](https://poser.pugx.org/rad/rad-framework/license)](https://packagist.org/packages/rad/rad-framework)
[![Maintainability](https://api.codeclimate.com/v1/badges/8e095176dd6216eea653/maintainability)](https://codeclimate.com/github/guillaumemonet/Rad/maintainability)

## What is RAD?
RAD for Rest API Dedicated Framework.

## Why create this?
I wanted to make a basic framework to learn and keep it as simple as possible.

Feel free to use it.

Any advice is welcome.

## Installation

[PHP](https://php.net) 7.1+ and [Composer](https://getcomposer.org) are required.

To get the latest version of RAD Micro-Framework, simply add the following line to the require block of your `composer.json` file:

```
"rad/rad-framework": "dev-master"
```

## History / RoadMap
* 2018 Q3 FINAL : Correct bugs and release first stable version
* 2018 Q2 BETA : Modularize Services
* 2018 Q1 ALPHA : Correct bugs , finalize PSR implementations
* 2017 Q4 Stabilizing framework, implements PSR


## Usage

```php
require(__DIR__ . "/../vendor/autoload.php");

use Rad\Api;
use Rad\Config\Config;
use Rad\Controller\Controller;
use Rad\Log\Log;
use Rad\Utils\Time;

/**
 * Simple example for testing purpose
 *
 * @author guillaume
 */
class MyController extends Controller {

    /**
     * @api 1
     * @get /helloworld/html/show/
     * @produce html
     */
    public function helloWorld() {
        return "<b>Hello World</b>";
    }

    /**
     * @api 1
     * @get /helloworld/json/show/
     * @middleware Rad\Middleware\Base\Pre_CheckConsume
     * @middleware Rad\Middleware\Base\Post_SetProduce
     * @produce json
     */
    public function jsonHelloWorld() {
        $this->response = $this->response->withAddedHeader('Hello', 'Moto');
        return array("Hello World");
    }

    /**
     * @api 1
     * @get /helloworld/([aA-zZ]*)/display/(.*)/
     * @middleware Rad\Middleware\Base\Pre_CheckConsume
     * @middleware Rad\Middleware\Base\Post_SetProduce
     * @produce html
     */
    public function namedHelloWorld() {
        return '<b>Hello World</b> ' . $this->route->getArgs()[0] . " to " . $this->route->getArgs()[1];
    }

    /**
     * @api 1
     * @get /server/
     * @middleware Rad\Middleware\Base\Pre_CheckConsume
     * @middleware Rad\Middleware\Base\Post_SetProduce
     * @produce html
     * @consume json
     */
    public function serverRequest() {
        return print_r($this->request->getHeader('HTTP_ACCEPT')[0], true);
    }

}

$time = Time::get_microtime();
$app = new Api();
$app->addControllers([MyController::class])
        ->run();
$ltime = Time::get_microtime();
Log::getHandler()->debug("API REQUEST [" . round($ltime - $time, 10) * 1000 . "] ms");
```

## How is works


## PSR Support

* [psr-3](http://www.php-fig.org/psr/psr-3/) Logger Interface
* [psr-4](http://www.php-fig.org/psr/psr-4/) Autoloader
* [psr-7](http://www.php-fig.org/psr/psr-7/) Http Message (Beta support)
* [psr-11](http://www.php-fig.org/psr/psr-11/) Container (WIP)
* [psr-16](http://www.php-fig.org/psr/psr-16/) Caching
* [psr-17 Draft](https://github.com/php-fig/fig-standards/tree/master/proposed/http-factory) Http Factory (WIP)



