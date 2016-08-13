<?php

namespace Manifesto\Library;

/**
 * URI Class
 *
 * @package     Manifesto
 * @subpackage  URI
 * @category    Library
 * @author      Noah Mormino
 * @link        http://noahmormino.com
 */

class URI {

    public function __construct()
    {
        //borrowed from AltoRouter
        $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';

        // strip base path from request url
        $requestUri = trim(substr($requestUri, strlen(BASE_DIR)), '/');

        $this->segments = explode('/', $requestUri);
    }
    
    public function segments()
    {
        return $this->segments;
    }

    public function segment($int) {

        if(!is_int($int))
        {
            throw new Exception(__CLASS__.'::'.__METHOD__.' requires an integer. '.gettype($int).' provided.');
        }
        
        //decrease $int by one.
        $int--;

        if(isset($this->segments[$int]))
        {
            return $this->segments[$int];
        }
        else
        {
            return false;
        }

    }
}