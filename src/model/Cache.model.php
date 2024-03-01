<?php

namespace Model;

use Controller\Settings\SensibleSettingsLoader;
use Redis;

/**
 * Class to interact with the cache.
 * 
 * Class is created following the singleton pattern see:
 * ```
 * Cache::getInstance();
 * ```
 * 
 * Class doesn't throw errors except on failed construct.
 * The reason is that it isn't mission critical.
 */
class Cache {

    private static Cache $instance;
    private Redis $redis;
    private function __construct()
    {
        // loading the settings for the connection
        (new SensibleSettingsLoader())->loadCacheSettings();

        // creating class for connection to redis
        $this->redis = new Redis();

        // connecting to redis database
        $this->redis->connect(self::$settings->username, self::$settings->port);
        // $this->redis->auth(self::$settings->password);
    }

    /**
     * Function to get instance of redis connection.
     */
    public static function getInstance() {
        if (!empty(self::$instance)) {
            return self::$instance;
        }

        self::$instance = new self;
        return self::$instance;
    }

    /**
     * ConnectionSettings class holding the credentials for the database.
     */
    private static ConnectionSettings $settings;

    /**
     * Function for loading the credentials before the connection to the database is made.
     */
    public static function loadSettings(ConnectionSettings $settings) {
        self::$settings = $settings;
    }

    /**
     * Function to get a page by id from cache.
     * 
     * @return Page|null If the page is found a object of the page is returned otherwise false.
     */
    public function getPage(int $pageId): Page|false {
        if (!$this->checkConnection()) {
            return false;
        }

        $page = $this->redis->get('page:' . $pageId);
        if (!$page) {
            return false;
        }

        return unserialize($page);
    }

    /**
     * Function to cache a page.
     * 
     * Page is cached for 5 minutes.
     * @return bool Returns true on success else false.
     */
    public function cachePage(Page &$page) {
        if (!$this->checkConnection()) {
            return false;
        }

        $key = 'page:' . $page->pageId;
        return $this->redis->set($key, serialize($page), 300);
    }

    public function removePageFromCache(int $pageId) {
        return $this->redis->del('page:' . $pageId);
    }

    /**
     * Function to check the connection to the redis server.
     */
    private function checkConnection() {
        return $this->redis->ping();
    }

    public function __destruct()
    {
        // closing the connection to the redis database
        $this->redis->close();
    }
}