<?php

namespace Manifesto\Driver\View;

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

class Native implements \Manifesto\Driver\View\ViewInterface
{
    private $viewPaths;
    private $baseFolder;
    private $viewModel;
    private $template;

    public function __construct($viewPaths = [])
    {
        $this->viewPaths = $viewPaths;
        $this->template = 'template';
    }

    public function setViewModel($viewModel)
    {
        $this->viewModel = Box::load('\Manifesto\ViewModel\\'.$viewModel, $viewModel);
    }

    public function setBaseFolder($baseFolder)
    {
        $this->baseFolder = trim($baseFolder, '/').'/';
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    private function processView($view, $vars) 
    {   
        $view = lcfirst(implode('', array_map('ucfirst', preg_split("/_|\\//", $view))));
        
        if(!is_null($this->viewModel))
        {
            if(method_exists($this->viewModel, $view))
            {
                /*
                * overwrite the original variables, this gives us the
                * chance to remove variables we don't want in the views
                * or to completely override the whole set of variables
                */

                $vars = call_user_func_array([$this->viewModel, $view], [$vars]);
            }
        }
        
        return $vars;
    }

    public function getView($view, $vars = false) {

        //make sure there are no additional slashes or spaces in the view
        $view = trim($view,'/ ');
        if ($vars)
        {
            extract($vars);
        }

        ob_start();
            include $this->find($view);
        return ob_get_clean();
    }

    public function find($view)
    {
        $found = false;

        foreach($this->viewPaths as $path) {
            $file = $path.$this->baseFolder.$view.'.php';

            if(file_exists($file)) {

                $found = true;
                return $file;
                break;
            }
        }

        if(!$found) {
            trigger_error('The requested view file "'.$view.'.php" was not found.');
        }
    }

    public function render($view, $vars=[], $returnContent=FALSE) {
        $vars = $this->processView($view, $vars);
        $vars['_partial'] = $view; //pass the view string in as a variable
        $vars['_view'] = $this;
        
        $view = $this->getView($this->template, $vars);
        
        if($returnContent)
        {
            return $view;
        }
        else
        {
            echo $view;
        }
    }

    public function partial($view, $vars=[], $returnContent=FALSE) {
        $vars = $this->processView($view, $vars);
        $view = $this->getView($view, $vars);
        
        if($returnContent)
        {
            return $view;
        }
        else
        {
            echo $view;
        }
    }
}