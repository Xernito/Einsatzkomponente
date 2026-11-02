<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Einsatzkomponente\Component\Einsatzkomponente\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;
/**
 * Alarmierungsart controller class.
 */
class AlarmierungsartController extends FormController
{
    public function __construct($config = [], $factory = null, $app = null, $input = null)
    {
        $this->view_list = 'alarmierungsarten';
        parent::__construct($config, $factory, $app, $input);
    }
}