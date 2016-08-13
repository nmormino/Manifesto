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

if(!function_exists('lang')) {

    function lang($key) {
        return Box::get('config')->get('lang.'.$key);
    }

    function getLang($file) {
        
        $load = Box::get('load');
        $config = Box::get('config');
        $path = $load->get('Language/english/'.$file.'_lang');
        require($path);

        $existing = configItem('lang');
        if(!$existing) $existing = [];

        foreach($lang as $key => $val)
        {
            $existing[$key] = $val;
        }

        $config->set('lang', $existing);
    }

}