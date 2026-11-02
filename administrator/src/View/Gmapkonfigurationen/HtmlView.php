<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Einsatzkomponente\Component\Einsatzkomponente\Administrator\View\Gmapkonfigurationen;

defined('_JEXEC') or die;

use Einsatzkomponente\Component\Einsatzkomponente\Administrator\Helper\EinsatzkomponenteHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Version;
/**
 * View class for a list of Einsatzkomponente.
 */
class HtmlView extends BaseHtmlView
{
	protected $items;
	protected $pagination;
    public $filterForm;
    public $activeFilters;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->getModel()->getState();
		$this->items		= $this->getModel()->getItems();
		$this->pagination	= $this->getModel()->getPagination();
		$this->filterForm = $this->getModel()->getFilterForm();
		$this->activeFilters = $this->getModel()->getActiveFilters();

        
        
		$this->addToolbar();
        
		$version = new Version;
        if ($version->isCompatible('3.0')) :
		endif;

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{

		$state	= $this->getModel()->getState();
		$canDo	= EinsatzkomponenteHelper::getActions();

		ToolbarHelper::title(Text::_('COM_EINSATZKOMPONENTE_TITLE_GMAPKONFIGURATIONEN'), 'gmapkonfigurationen');

        //Check if the form exists before showing the add/edit buttons
        if ($canDo->get('core.create') || $canDo->get('core.edit')) {


		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    ToolbarHelper::editList('gmapkonfiguration.edit','JTOOLBAR_EDIT');
		    }

        }

        
        //Show trash and delete for components that uses the state field

        

        
	}
    

    
}
