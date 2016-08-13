<?php

/**
 * Config Helper
 *
 * @package     Manifesto
 * @subpackage  Config
 * @category    Helper
 * @author      Noah Mormino
 * @link        http://noahmormino.com
 */
use Manifesto\Library\Box;

if(!function_exists('configItem')) {
    
    function configItem($key, $val=null) {
        if($val !== null)
        {
            return Box::get('config')->{$key} = $val;
        }
        else
        {
            return Box::get('config')->{$key};
        }
    }
    
}

if(!function_exists('configFile')) {
    
    function configFile($filename) {
        return Box::get('config')->load($filename);
    }
    
}

if(!function_exists('configAll')) {
    
    function configAll($filename) {
        return Box::get('config')->all();
    }

}