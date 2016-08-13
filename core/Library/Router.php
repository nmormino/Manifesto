<?php

namespace Manifesto\Library;

/**
 * Router Class
 *
 * @package     Manifesto
 * @subpackage  Router
 * @category    Router
 * @author      Noah Mormino
 * @link        http://noahmormino.com
 */

class Router {

    public function __construct(\Manifesto\Library\Load $load, \Manifesto\Library\Config $config, \Manifesto\Library\Manifest $manifest, \AltoRouter $router)
    {
        $this->load = $load;
        $this->config = $config;
        $this->manifest = $manifest;
        $this->router = $router;
    }

    public function initialize()
    {
        //set the base path
        $this->router->setBasePath(BASE_DIR);

        //set the homepage route
        //$this->router->map('GET|POST', '/', 'Manifesto\Controller\Page#homepage');

        //pull in the routes from the manifest
        foreach($this->manifest->get('routes') as $route)
        {
            $this->router->map($route[0], $route[1], $route[2]);
        }

        $this->router->map('GET|POST', '/', '\Manifesto\Controller\Dashboard#index');
        $match = $this->router->match();

        // call a closure
        if( $match && is_callable( $match['target'] ) ) {
            call_user_func_array( $match['target'], $match['params'] );
        }

        // parse a string and call it
        elseif($match && is_string($match['target']))
        {
            $target = explode('#', $match['target']);

            try {
                $class = Box::load($target[0]);
                call_user_func_array([$class, $target[1]], $match['params']);

            } catch(Exception $e) {
                var_dump($e);
                show404();
            }
        }

        // throw a 404 error
        else
        {
            $error = new \Manifesto\Controller\Error;
            $error->index();
        }
    }
}