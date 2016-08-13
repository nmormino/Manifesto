<?php

namespace Manifesto\Controller;
/**
 * Base Class
 *
 * @package     Manifesto
 * @subpackage  Controllers
 * @category    Admin
 * @author      Noah Mormino
 * @link        http://noahmormino.com
 */

use Manifesto\Library\Box as Box;

class Base {

    public function __construct()
    {
        $this->view = Box::load('\Manifesto\Library\View', 'view');
        $this->load = Box::get('load');
    }
}