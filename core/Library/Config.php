<?php

namespace Manifesto\Library;

/**
 * Config Class
 *
 * @package     Manifesto
 * @subpackage  Libraries
 * @category    Config
 * @author      Noah Mormino
 * @link        http://noahmormino.com
 */

use Illuminate\Support\Arr;

class Config {

    private $items;
    private $load;
    private static $instance;

    public function __construct(\Manifesto\Library\Load $load) {
        $this->load = $load;
        $this->items = [];
    }

    //load a config file
    public function load($file)
    {
        require $this->load->config($file);
        $this->items = array_merge($this->items, $config);
    }

    public function set($key, $val) {
        $this->items[$key] = $val;
    }

    public function get($key)
    {
        return array_get($this->items, $key);
    }

    public function all()
    {
        return $this->items;
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __set($key, $val)
    {
        $this->set($key, $val);
    }

}