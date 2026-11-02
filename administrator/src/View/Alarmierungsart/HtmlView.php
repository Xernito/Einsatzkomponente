<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Einsatzkomponente\Component\Einsatzkomponente\Administrator\View\Alarmierungsart;

defined('_JEXEC') or die;

use Einsatzkomponente\Component\Einsatzkomponente\Administrator\Helper\EinsatzkomponenteHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
/**
 * View to edit
 */
class HtmlView extends BaseHtmlView
{
	protected $state;
	protected $item;
	protected $form;
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state	= $this->getModel()->getState();
		$this->item		= $this->getModel()->getItem();
		$this->form		= $this->getModel()->getForm();
		$this->addToolbar();
		parent::display($tpl);
	}
	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		Factory::getApplication()->getInput()->set('hidemainmenu', true);
		$user		= Factory::getApplication()->getIdentity();
		$isNew		= ($this->item->id == 0);
        if (isset($this->item->checked_out)) {
		    $checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }
		$canDo		= EinsatzkomponenteHelper::getActions();
		ToolbarHelper::title(Text::_('COM_EINSATZKOMPONENTE_TITLE_ALARMIERUNGSART'), 'alarmierungsart');
		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{
			ToolbarHelper::apply('alarmierungsart.apply', 'JTOOLBAR_APPLY');
			ToolbarHelper::save('alarmierungsart.save', 'JTOOLBAR_SAVE');
		}
		if (!$checkedOut && ($canDo->get('core.create'))){
			ToolbarHelper::custom('alarmierungsart.save2new', 'save-new', '', 'JTOOLBAR_SAVE_AND_NEW', false);
		}
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			ToolbarHelper::custom('alarmierungsart.save2copy', 'save-copy', '', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
		if (empty($this->item->id)) {
			ToolbarHelper::cancel('alarmierungsart.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			ToolbarHelper::cancel('alarmierungsart.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
