<?php

namespace Manifesto\Library;

/**
 * Manifest Class
 *
 * @package     Manifesto
 * @subpackage  Manifest
 * @category    Manifest
 * @author      Noah Mormino
 * @link        http://noahmormino.com
 */

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Manifest {
    
    protected $adminNavItems;
    protected $themeShortcodes;
    protected $routes;
    protected $classes;
    protected $modules;
    protected $moduleDirectories;

    public function __construct(\Manifesto\Library\Load $load, \Manifesto\Library\Config $config)
    {
        $this->load = $load;
        $this->config = $config;
        
        $this->paymentModules = [];
        $this->shippingModules = [];
        $this->adminNavItems = [];
        $this->themeShortcodes = [];
        $this->routes = [];
        $this->modules = [];
        $this->classes = [];
    }

    public function initialize()
    {
        //if the file does not exist, or if we are in dev mode, run the generate script
        if(!file_exists(APP_PATH.'Config/manifest.php') || $this->config->devmode)
        {
            Log::set('Manifest generation commencing.');
            $this->generate();
        }
        
        //require the manifest file
        require APP_PATH.'Config/manifest.php';

        //add the paths to the Loader.
        foreach($this->modules as $module)
        {
            $this->load->addPath($module);
        }
    }

    public function generate() {

        $adminNavItems = [];
        $paymentModules = [];
        $shippingModules = [];

        foreach($this->config->moduleDirectories as $moduleDirectory)
        {
            foreach(array_diff( scandir($moduleDirectory), ['..', '.']) as $availableModule)
            {
                if(is_dir($moduleDirectory.'/'.$availableModule))
                {
                    $this->modules[] = $moduleDirectory.'/'.$availableModule;

                    $dir_iterator = new RecursiveDirectoryIterator($moduleDirectory.'/'.$availableModule);                  
                    $iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);

                    foreach ($iterator as $file) {

                        if(!is_dir($file))
                        {
                            $ext = pathinfo($file,PATHINFO_EXTENSION);
                            $filename = pathinfo($file, PATHINFO_FILENAME);
                            if($ext == 'php')
                            {
                                if($filename == 'manifest')
                                {
                                    include($file);
                                }
                                $this->getPhpClasses((string)$file);
                            }
                        }
                    }
                }
            }
        }

        foreach($adminNavItems as $adminNavItem)
        {
            //re-add them into the structured array
            $this->addNavMenuItem($adminNavItem);
        }

        $manifest = "<?php ";
        $manifest .= '//ClassMap for autoloader'."\n".'$this->classes = '.var_export($this->classMap, true).';';
        $manifest .= "\n\n".'//Complete Module List'."\n".'$this->modules = '.var_export($this->modules,true).';';
        $manifest .= "\n\n".'//Defined Routes'."\n".'$this->routes = '.var_export($routes,true).';';
        
        //change out the hard coded paths with the APP_PATH
        $manifest = str_replace('\''.APP_PATH, 'APP_PATH.\'', $manifest);

        Log::set('Write manifest file to config directory.');
        //generate the autoload file
        file_put_contents(APP_PATH.'Config/manifest.php', $manifest);
    }

    public function get($prop)
    {
        if(isset($this->{$prop}))
        {
            return $this->{$prop};
        }
        else
        {
            trigger_error('The property you\'re requesting does not exist in the manifest object.', E_USER_NOTICE);
        }
    }

    private function getPhpClasses($file) {

        $phpcode = file_get_contents($file);

        $namespace = 0;
        $tokens = token_get_all($phpcode);
        $count = count($tokens);
        $dlm = false;
        for ($i = 2; $i < $count; $i++)
        {
            if ((isset($tokens[$i - 2][1]) && ($tokens[$i - 2][1] == "phpnamespace" || $tokens[$i - 2][1] == "namespace")) || ($dlm && $tokens[$i - 1][0] == T_NS_SEPARATOR && $tokens[$i][0] == T_STRING))
            {
                if (!$dlm)
                {
                    $namespace = 0; 
                }
                if (isset($tokens[$i][1]))
                {
                    $namespace = $namespace ? $namespace . "\\" . $tokens[$i][1] : $tokens[$i][1];
                    $dlm = true; 
                }
            }
            elseif ($dlm && ($tokens[$i][0] != T_NS_SEPARATOR) && ($tokens[$i][0] != T_STRING))
            {
                $dlm = false; 
            }
            if (($tokens[$i - 2][0] == T_CLASS || (isset($tokens[$i - 2][1]) && $tokens[$i - 2][1] == "phpclass")) && $tokens[$i - 1][0] == T_WHITESPACE && $tokens[$i][0] == T_STRING)
            {
                $class_name = $tokens[$i][1]; 
                
                if($namespace != '')
                {
                    $this->classMap[$namespace.'\\'.$class_name] = $file;
                }
                else
                {
                    $this->classMap[$class_name] = $file;
                }
            }
        } 
    }
}