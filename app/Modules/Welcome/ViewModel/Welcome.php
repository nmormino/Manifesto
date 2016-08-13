<?php

namespace Manifesto\ViewModel;

/**
 * Dashboard Class
 *
 * @package     Manifesto
 * @subpackage  ViewModels
 * @category    Dashboard
 * @author      Noah Mormino
 * @link        http://noahmormino.com
 */

class Dashboard {

    public function dashboard($data)
    {
        $data['pageTitle'] = lang('dashboard');
        return $data;
    }

}
