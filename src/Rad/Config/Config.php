<?php

/*
 * The MIT License
 *
 * Copyright 2017 Guillaume Monet.
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

namespace Rad\Config;

use Rad\Error\ConfigurationException;

/**
 *
 */
final class Config {

    public static $config = [];

    private function __construct() {
        
    }

    private function __clone() {
        
    }

    /**
     * Load config file.
     *
     * @param string $filename
     * @deprecated
     */
    public static function loadIni($filename) {
        self::$config = array_merge(self::$config, parse_ini_file($filename, true));
    }

    /**
     * 
     * @param type $jsonFilename
     * @throws ConfigurationException
     */
    public static function load(string $jsonFilename, $append = false) {
        self::$config = json_decode(file_get_contents($jsonFilename));
        if (json_last_error() > 0) {
            throw new ConfigurationException('Configuration can\'t be loaded');
        }
    }

    /**
     * Return current config
     * @param string $section
     * @param string $row
     * @return string
     */
    public static function get(string $section, $row = null) {
        if ($row === null) {
            return isset(self::$config[$section]) ? self::$config[$section] : null;
        } else {
            return isset(self::$config[$section][$row]) ? self::$config[$section][$row] : null;
        }
    }

    public static function getConfig() {
        return self::$config;
    }

    /**
     * 
     * @param string $serviceType
     * @param string $serviceName
     * @return type
     */
    public static function getServiceConfig(string $serviceType, string $serviceName = null) {
        if ($serviceName === null) {
            return self::$config->services->{$serviceType};
        } else {
            return self::$config->services->{$serviceType}->handlers->{$serviceName};
        }
    }

    public static function getApiConfig($name = null) {
        if (!isset(self::$config->api)) {
            throw new \ErrorException('Not Api Config found');
        }
        return $name !== null ? self::$config->api->{$name} : self::$config->api;
    }

    /**
     *
     * @param type $section
     * @param type $row
     * @return type
     */
    public static function has(string $section, $row = null) {
        return $row === null ? isset(self::$config[$section]) : isset(self::$config[$section][$row]);
    }

    /**
     *
     * @param type $section
     * @param type $row
     * @param type $value
     */
    public static function set(string $section, string $row, $value) {
        self::$config[$section][$row] = $value;
    }

}
