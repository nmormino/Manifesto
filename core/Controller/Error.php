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

use \Manifesto\Controller\Base;

class Error extends Base {

	public function index()
	{
		header("HTTP/1.0 404 Not Found");
		$this->view->render('404');
	}

}
