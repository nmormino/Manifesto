<?php

namespace Manifesto;

/**
 * Bootstrap Class
 *
 * @package     Manifesto
 * @subpackage  Bootstrap
 * @category    Bootstrap
 * @author      Noah Mormino
 * @link        http://noahmormino.com
 */

use Manifesto\Library\Config;
use Manifesto\Library\Load;
use Manifesto\Library\Log;
use Manifesto\Library\Manifest;
use Manifesto\Library\Router;
use Manifesto\Library\Box;
use AltoRouter;

class Bootstrap {

    public function initialize(){

        Log::set('Bootstrap Initializing.');

        Log::set('Instantiate Load class');
        $load = Box::load('Manifesto\Library\Load', 'load');

        Log::set('Load global helpers');
        $load->helper('System');
        $load->helper('Config');
        $load->helper('URI');


        Log::set('Instantiate Config class');
        $config = Box::load('Manifesto\Library\Config', 'config');
        $config->load('config'); // default config file.

        Log::set('Instantiate Manifest Class.');
        $manifest = Box::load('Manifesto\Library\Manifest', 'manifest');
        $manifest->initialize();

        Log::set('Register Manifesto Autoloader');
        spl_autoload_register(function($class) use ($manifest) {
            $classes = $manifest->get('classes');
            if(isset($classes[$class]))
            {
                include($classes[$class]);
            }
        });

        Log::set('Instantiate Router Class.');
        Box::load('Manifesto\Library\Router')->initialize();
    }
}