<?php

namespace Manifesto\Library;

/**
 * View Class
 *
 * @package     Manifesto
 * @subpackage  Libraries
 * @category    View
 * @author      Noah Mormino
 * @link        http://noahmormino.com
 */

use \Manifesto\Library\Box;
use \Manifesto\Library\Load;

class View
{
    public $viewPaths;
    public $load;
    public $driver;

    public function __construct(Load $load, $driver = 'Native')
    {
        $this->load = $load;
        $this->driver = $driver;

        //default view paths
        $this->viewPaths = [
            APP_PATH.'Views/'
        ];
        
        //module view paths
        foreach($this->load->getPaths() as $path){
            if(is_dir($path.'Views/'))
            {
                array_push($this->viewPaths, $path.'Views/');
            }
        }

        $driverClass = 'Manifesto\\Driver\View\\'.$driver;

        $this->driver = Box::load($driverClass, $driverClass, ['viewPaths' => $this->viewPaths]);
    }

    public function __call($method, $args) {

        if(method_exists($this->driver, $method))
        {
            return call_user_func_array([$this->driver, $method], $args);
        }
        else
        {
            trigger_error('Unknown function '.__CLASS__.':'.$method, E_USER_ERROR);
        }
    }

    public function __get($key)
    {
        return $this->driver->{$key};
    }

    public function __set($key, $val)
    {
        $this->driver->{$key} = $val;
    }
}