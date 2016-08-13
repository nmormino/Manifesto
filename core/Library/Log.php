<?php

namespace Manifesto\Library;

/**
 * Log Class
 *
 * @package     Manifesto
 * @subpackage  Libraries
 * @category    Log
 * @author      Noah Mormino
 * @link        http://noahmormino.com
 */

class Log {

    private $log;
    private static $instance;

    private function __construct(){
        $this->log = [];
    }

    private function __clone(){

    }

    private function __wakeup(){

    }

    public static function instance()
    {
        if(null === static::$instance)
        {
            static::$instance = new Log;
        }
    }

    static public function set($str) {
        $bt = debug_backtrace();
        static::$instance->log[] = ['file'=>$bt[0]['file'], 'line'=>$bt[0]['line'], 'log'=>$str];
    }

    static public function get() {
        return static::$instance->log;
    }
}