<?php

namespace App;

class Cache {
    private $cache = [];
    private $file = __DIR__ . '/../cache/app.json';
    private static $instance;

    public function __construct() {
        $this->load();
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance  = new self;
        }

        return self::$instance;
    }

    private function load() {
        if (!file_exists($this->file)) {
            return touch($this->file);
        }

        $this->cache = json_decode(file_get_contents($this->file), true);
    }

    public function getItem($key = null, $default = null) {
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        if (!is_null($default)) {
            return $default;
        }
    }

    public function setItem($key, $value) {
        $this->cache[$key] = $value;
        file_put_contents($this->file, json_encode($this->cache));
    }

    public function allItems() {
        return $this->cache;
    }

    public static function has($key, $default = null) {
        $instance = self::getInstance();

        $item =  $instance->getItem($key, $default);
        return !is_null($item);
    }

    public static function get($key, $default = null) {
        $instance = self::getInstance();

        return $instance->getItem($key, $default);
    }

    public static function set($key, $value) {
        $instance = self::getInstance();

        return $instance->setItem($key,  $value);
    }
}