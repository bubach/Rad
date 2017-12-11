<?php

require(__DIR__ . "/../vendor/autoload.php");

use Rad\Api;
use Rad\Config\Config;
use Rad\Controller\Controller;
use Rad\Log\Log;
use Rad\Utils\Time;

/*
 * The MIT License
 *
 * Copyright 2017 guillaume.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

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
     * @produce json
     */
    public function jsonHelloWorld() {
        $this->response = $this->response->withAddedHeader('Hello', 'Moto');
        $std = new stdClass();
        $std->toto = "toto/fdsf   sdf://";
        $std->arr = ["toto ","titi"];
        return [$std, $std];
    }

    /**
     * @api 1
     * @get /helloworld/([aA-zZ]*)/display/(.*)/
     * @produce html
     */
    public function namedHelloWorld() {
        return '<b>Hello World</b> ' . $this->route->getArgs()[0] . " to " . $this->route->getArgs()[1];
    }

    /**
     * @api 1
     * @get /server/
     * @xhr true
     * @produce json
     */
    public function serverRequest() {
        return print_r($this->request->getHeader('HTTP_ACCEPT')[0], true);
    }

}

$time = Time::get_microtime();
Config::set("cache", "type", "file");
Config::set("cache", "enabled", 0);
Config::set("log", "type", "file");
Config::set("log", "error", 1);
Config::set("log", "debug", 1);
Config::set("log", "enabled", 1);
$app = new Api();
$app->addControllers([MyController::class])
        ->run();
$ltime = Time::get_microtime();
Log::getHandler()->debug("API REQUEST [" . round($ltime - $time, 10) * 1000 . "] ms");