<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Einsatzkomponente\Component\Einsatzkomponente\Administrator\View\Einsatzfahrzeuge;

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
		ToolbarHelper::title(Text::_('COM_EINSATZKOMPONENTE_TITLE_EINSATZFAHRZEUGE'), 'einsatzfahrzeuge');
        //Check if the form exists before showing the add/edit buttons
        if ($canDo->get('core.create') || $canDo->get('core.edit')) {
            if ($canDo->get('core.create')) {
			    ToolbarHelper::addNew('einsatzfahrzeug.add','JTOOLBAR_NEW');
		    }
		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    ToolbarHelper::editList('einsatzfahrzeug.edit','JTOOLBAR_EDIT');
		    }
        }
		if ($canDo->get('core.edit.state')) {
            if (isset($this->items[0]->state)) {
			    ToolbarHelper::divider();
			    ToolbarHelper::custom('einsatzfahrzeuge.publish', 'publish', '','JTOOLBAR_PUBLISH', true);
			    ToolbarHelper::custom('einsatzfahrzeuge.unpublish', 'unpublish', '', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                ToolbarHelper::deleteList('', 'einsatzfahrzeuge.delete','JTOOLBAR_DELETE');
            }
            if (isset($this->items[0]->state)) {
			    ToolbarHelper::divider();
			    ToolbarHelper::archiveList('einsatzfahrzeuge.archive','Fahrzeug a.D.');
            }
            if (isset($this->items[0]->checked_out)) {
            	ToolbarHelper::custom('einsatzfahrzeuge.checkin', 'checkin', '', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			    ToolbarHelper::deleteList('', 'einsatzfahrzeuge.delete','JTOOLBAR_EMPTY_TRASH');
			    ToolbarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			    //ToolbarHelper::trash('einsatzfahrzeuge.trash','JTOOLBAR_TRASH');
                ToolbarHelper::deleteList('', 'einsatzfahrzeuge.delete','JTOOLBAR_DELETE');
			    ToolbarHelper::divider();
		    }
        }
		if ($canDo->get('core.admin')) {
			ToolbarHelper::preferences('com_einsatzkomponente');
		}
        
		$version = new Version;
        if ($version->isCompatible('3.0')) :
		$options = array ();
		$options[] = HTMLHelper::_('select.option', '1', 'JPUBLISHED');
		$options[] = HTMLHelper::_('select.option', '0', 'JUNPUBLISHED');
		$options[] = HTMLHelper::_('select.option', '2', 'Fahrzeug a.D.');
		$options[] = HTMLHelper::_('select.option', '-2', 'JTRASHED');
		$options[] = HTMLHelper::_('select.option', '*', 'JALL');
		endif;
		
        
	}
    
    
}
