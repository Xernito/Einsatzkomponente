<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Einsatzkomponente\Component\Einsatzkomponente\Administrator\View\Kontrollcenter;

defined('_JEXEC') or die;

use Einsatzkomponente\Component\Einsatzkomponente\Administrator\Helper\EinsatzkomponenteHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
/**
 * View class for a list of Einsatzkomponente.
 */
class HtmlView extends BaseHtmlView
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $params;
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->params = ComponentHelper::getParams('com_einsatzkomponente');
		
		//$this->gmap_config = EinsatzkomponenteHelper::load_gmap_config(); // GMap-Config aus helper laden 
		
		// Check for errors.
		// if (count($errors = $this->get('Errors'))) {
			// throw new \Exception(implode("\n", $errors));
		// }
        
        
		$this->addToolbar();
		
		
		parent::display($tpl);
	}
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		ToolbarHelper::title(Text::_('COM_EINSATZKOMPONENTE_TITLE_KONTROLLCENTER'), 'organisationen');
		ToolbarHelper::preferences('com_einsatzkomponente');
        
		 
        
        
	}
    
    
}
