<?php

namespace Manifesto\Driver\View;

/**
 * View Class
 *
 * @package     Manifesto
 * @subpackage  Interface
 * @category    View
 * @author      Noah Mormino
 * @link        http://noahmormino.com
 */

interface ViewInterface
{
    public function setViewModel($viewModel);
    public function setBaseFolder($baseFolder);
    public function render($view, $vars, $returnContent);
    public function partial($view, $vars, $returnContent);
}