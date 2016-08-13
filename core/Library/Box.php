<?php namespace Manifesto\Library;

/**
 * Box Class
 *
 * @package     Manifesto
 * @subpackage  Library
 * @category    Config
 * @author      Noah Mormino
 * @link        http://noahmormino.com
 */

class Box {

    private static $items = [];

    private function __construct(){}
    private function __clone(){}
    private function __wakeup(){}

    public static function load($classname, $alias = false, $properties=[])
    {
        if(!$alias)
        {
            /**********************************************
            convert the classname to a camel case alias ex. if an alias does not exist
            Manifesto/Library/Router
            goCartLibraryRouter
            **********************************************/
            $alias = lcfirst(str_replace('/', '', $classname));
        }

        if(isset(self::$items[$alias]))
        {
            return self::$items[$alias];
        }

        $class = new \ReflectionClass($classname);
        $constructor = $class->getConstructor();
        if($constructor)
        {
            $requiredParams = $constructor->getParameters();

            $params = [];
            //Find out if any of the requirements are not loaded in
            foreach($requiredParams as $p) {
                
                $paramClass = $p->getClass();

                if(is_object($paramClass))
                {
                    $paramClass = $paramClass->name;
                }

                if(!isset($params[$p->name])) {
                    if(class_exists($paramClass))
                    {
                        
                        if(!isset(self::$items[$p->name]))
                        {
                            Box::load($paramClass, $p->name);    
                        }
                        
                    }
                }
            }

            //var_dump(array_keys(self::$items));
            $params = [];

            foreach($requiredParams as $p)
            {
                $paramClass = $p->getClass();

                if(is_object($paramClass))
                {
                    $paramClass = $paramClass->name;
                }

                foreach(self::$items as $key => $item)
                {
                    //allow override by default
                    if(array_key_exists($p->name, $properties))
                    {
                        $params[$p->name] = $properties[$p->name];
                    }
                    //otherwise look for the existing class in the box that shares the same key name
                    elseif(get_class($item) == $paramClass && $p->name == $key)
                    {
                        $params[$p->name] = $item;
                    }
                }
            }

            self::$items[$alias] = $class->newInstanceArgs($params);

        }
        else
        {
            //no constructor
            self::$items[$alias] = $class->newInstance();
        }
        

        return self::$items[$alias];
    }

    public static function get($key)
    {
        return array_get(self::$items, $key);
    }

    public static function all()
    {
        return self::$items;
    }

    //manually set a new object
    public static function set($key, $obj)
    {
        self::$items[$key] = $obj;
    }

    public static function destroy($key)
    {
        unset(self::$items[$key]);
    }
}