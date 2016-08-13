<?php

namespace Manifesto\Controller;

/**
 * Welcome Class
 *
 * @package     Manifesto
 * @subpackage  Controllers
 * @category    Welcome
 * @author      Noah Mormino
 * @link        http://noahmormino.com
 */

use \Manifesto\Controller\Base;
use Manifesto\Library\Box;

class Welcome extends Base {

    public function index()
    {
        $this->view->render('welcome');
    }
    
}