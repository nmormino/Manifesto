<?php

namespace Manifesto\Library;

/**
 * Load Class
 *
 * @package     Manifesto
 * @subpackage  Libraries
 * @category    Load
 * @author      Noah Mormino
 * @link        http://noahmormino.com
 */

class Load {

    protected $paths;

    public function __construct($paths = [])
    {
        $this->paths = [];

        $this->addPath(APP_PATH);
        $this->addPath(BASE_PATH.'core');
        foreach($paths as $path)
        {
            $this->addPath($path);
        }
    }

    public function getPaths()
    {
        return $this->paths;
    }

    public function config($filename)
    {
        return $this->get('Config/'.$filename);
    }

    //helper loader automatically includes the file
    public function helper($filenames)
    {
        if(!is_array($filenames))
        {
            $filenames = [$filenames];
        }
        foreach($filenames as $filename)
        {
            include($this->get('Helpers/'.$filename));
        }
    }

    //get all instances of a filename
    protected function getAll($filename, $ext='.php')
    {
        $found = [];
        foreach ($this->paths as $path)
        {
            if(file_exists($path.$filename.$ext))
            {
                $found[] = $path.$filename.$ext;
            }
        }

        return $found;
    }

    //return a single file
    public function get($filename, $ext='.php')
    {
        $found = $this->getAll($filename, $ext);

        $size = count($found);
        if($size > 1)
        {
            $error = 'More than one file named "'.$filename.'" was discovered.';
            $count = 1;
            foreach($found as $file)
            {
                $error += '<br>'.$count.') '.$file;
            }

            trigger_error($error, E_USER_NOTICE);
        }
        elseif($size == 0)
        {
            trigger_error('The requested file "'.$filename.$ext.'" was not discovered.', E_USER_NOTICE);
        }
        else
        {
            return $found[0];
        }
    }

    //check if the path is loaded
    private function pathLoaded($path)
    {
        if(in_array($path, $this->paths))
        {
            trigger_error('The path "'.$path.'" is already loaded.', E_USER_NOTICE);
            return false;
        }
        else
        {
            return true;
        }
    }

    //check if the path exists at all
    private function pathExists($path)
    {
        if(!is_dir($path))
        {
            trigger_error('The path "'.$path.'" path does not exist.', E_USER_NOTICE);
            return false;
        }
        else
        {
            return true;
        }
    }

    public function addPath($path)
    {
        //ensure the path has the correct slashes
        $path = '/'.trim($path,'/').'/';

        //check if the path exists and is valid
        if($this->pathLoaded($path) && $this->pathExists($path))
        {
            $this->paths[] = $path;
        }
    }
}