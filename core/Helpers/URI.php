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

if(!function_exists('getURL')) {
    
    function getURL($key = '/', $prefix = URL_PROTOCOL)
    {
        return trim($prefix.'://'.BASE_URL.trim($key, '/'), '/');
    }

}

if(!function_exists('redirect')) {
    
    function redirect($key, $prefix = URL_PROTOCOL)
    {
        header('Location: '.getURL($key, $prefix));
        die;
    }

}

if(!function_exists('requestURI')) {
    
    function requestURI()
    {
        if(BASE_DIR !== '')
        {
            $pos = strpos($_SERVER['REQUEST_URI'], BASE_DIR);
            return substr_replace($_SERVER['REQUEST_URI'], '', $pos, strlen(BASE_DIR));    
        }
        else
        {
            return $_SERVER['REQUEST_URI'];
        }
        
    }

}

if(!function_exists('show404')) {
    
    function show404()
    {
        header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
        $page = \Manifesto\Library\Box::load('Manifesto\Controller\Page', 'Page');
        $page->show404();
    }
}