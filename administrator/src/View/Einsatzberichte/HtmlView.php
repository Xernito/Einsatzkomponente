<?php

/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Einsatzkomponente\Component\Einsatzkomponente\Administrator\View\Einsatzberichte;

defined('_JEXEC') or die;

use Einsatzkomponente\Component\Einsatzkomponente\Administrator\Helper\EinsatzkomponenteHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Database\DatabaseInterface;
/**
 * View class for a list of Einsatzkomponente.
 */
class HtmlView extends BaseHtmlView {

    protected $items;
    protected $pagination;
    public $filterForm;
    public $activeFilters;
    protected $state;
	protected $params;
    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->getModel()->getState();
        $this->items = $this->getModel()->getItems();
        $this->pagination = $this->getModel()->getPagination();
        $this->filterForm = $this->getModel()->getFilterForm();
        $this->activeFilters = $this->getModel()->getActiveFilters();
		$this->params = ComponentHelper::getParams('com_einsatzkomponente');

        $this->addToolbar();

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
		$state	= $this->getModel()->getState();
		$canDo	= EinsatzkomponenteHelper::getActions();
		ToolbarHelper::title(Text::_('COM_EINSATZKOMPONENTE_TITLE_EINSATZBERICHTE'), 'einsatzberichte');
        //Check if the form exists before showing the add/edit buttons
        if ($canDo->get('core.create') || $canDo->get('core.edit')) {
            if ($canDo->get('core.create')) {
			    ToolbarHelper::addNew('einsatzbericht.add','JTOOLBAR_NEW');
		    }
		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    ToolbarHelper::editList('einsatzbericht.edit','JTOOLBAR_EDIT');
		    }
        }
		if ($canDo->get('core.edit.state')) {
            if (isset($this->items[0]->state)) {
			    ToolbarHelper::divider();
			    ToolbarHelper::custom('einsatzberichte.publish', 'publish', '','JTOOLBAR_PUBLISH', true);
			    ToolbarHelper::custom('einsatzberichte.unpublish', 'unpublish', '', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                ToolbarHelper::deleteList('','einsatzberichte.delete','JTOOLBAR_DELETE');
            }
            if (isset($this->items[0]->checked_out)) {
            	ToolbarHelper::custom('einsatzberichte.checkin', 'checkin', '', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			    ToolbarHelper::deleteList('','einsatzberichte.delete','JTOOLBAR_EMPTY_TRASH');
			    ToolbarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			    //ToolbarHelper::trash('einsatzberichte.trash','JTOOLBAR_TRASH');
                ToolbarHelper::deleteList('','einsatzberichte.delete','JTOOLBAR_DELETE');
			    ToolbarHelper::divider();
		    }
        }
		if ($canDo->get('core.admin')) {
			ToolbarHelper::preferences('com_einsatzkomponente');
				if ($this->params->get('send_mail_backend','0')) : 
				ToolbarHelper::custom( 'einsatzberichte.sendMail', 'edit','edit', 'COM_EINSATZKOMPONENTE_ALS_EMAIL_VERSENDEN',  true );
				endif;
		}
		
		if ($canDo->get('core.create')) {
				ToolbarHelper::custom( 'einsatzberichte.article', 'edit','edit', 'COM_EINSATZKOMPONENTE_ALS_JOOMLA_ARTIKEL_ERSTELLEN',  true );
		}
		ToolbarHelper::custom( 'einsatzberichte.pdf', 'upload','upload', 'COM_EINSATZKOMPONENTE_ALS_PDF_EXPORTIEREN',  true );
		
            if ($canDo->get('core.create')) :
            if (isset($this->items[0]->state)) {
			    ToolbarHelper::divider();
			    ToolbarHelper::archiveList('einsatzberichte.archive','COM_EINSATZKOMPONENTE_ALS_FOLGEEINSATZ_MARKIEREN');
            }
			endif;
    }

}
