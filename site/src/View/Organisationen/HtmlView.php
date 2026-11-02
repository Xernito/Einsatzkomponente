<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Einsatzkomponente\Component\Einsatzkomponente\Site\View\Organisationen;

defined('_JEXEC') or die;

use Einsatzkomponente\Component\Einsatzkomponente\Administrator\Helper\EinsatzkomponenteHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
/**
 * View class for a list of Einsatzkomponente.
 */
class HtmlView extends BaseHtmlView
{
	protected $items;
	protected $pagination;
	protected $state;
    protected $params;
    protected $version;
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
        $app                = Factory::getApplication();
        
        $this->state		= $this->getModel()->getState();
        $this->items		= $this->getModel()->getItems();
        $this->pagination	= $this->getModel()->getPagination();
        $this->params       = $app->getParams('com_einsatzkomponente');
		//print_r ($this->items);break;
		
		//Komponentenversion aus Datenbank lesen
		$this->version 		= EinsatzkomponenteHelper::getVersion (); 

		$document = Factory::getApplication()->getDocument();
        // Import CSS
		$document->getWebAssetManager()->useStyle('com_einsatzkomponente.einsatzkomponente');
		$document->addStyleSheet(\Joomla\CMS\Uri\Uri::root(true) . '/media/com_einsatzkomponente/css/responsive.css');
		// Bootstrap laden
		
		if ($this->params->get('display_orga_bootstrap','0') == '1') :
		endif;
		if ($this->params->get('display_orga_bootstrap','0') == '2') :
		$document->addStyleSheet(\Joomla\CMS\Uri\Uri::root(true) . '/media/com_einsatzkomponente/css/bootstrap/bootstrap.min.css');
		$document->addStyleSheet(\Joomla\CMS\Uri\Uri::root(true) . '/media/com_einsatzkomponente/css/bootstrap/bootstrap-responsive.min.css');
		endif;
		
		// Import CSS aus Optionen
		if (trim((string) $this->params->get('organisationen_css','')) !== '') {
			$document->getWebAssetManager()->addInlineStyle($this->params->get('organisationen_css',''));
		} 
        
        
        $this->_prepareDocument();
        parent::display($tpl);
	}
	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app	= Factory::getApplication();
		$menus	= $app->getMenu();
		$title	= null;
		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', Text::_('com_einsatzkomponente_DEFAULT_PAGE_TITLE'));
		}
		$title = $this->params->get('page_title', '');
		if (empty($title)) {
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1) {
			$title = Text::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2) {
			$title = Text::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}
		$this->getDocument()->setTitle($title);
		if ($this->params->get('menu-meta_description'))
		{
			$this->getDocument()->setDescription($this->params->get('menu-meta_description'));
		}
		if ($this->params->get('menu-meta_keywords'))
		{
			$this->getDocument()->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}
		if ($this->params->get('robots'))
		{
			$this->getDocument()->setMetadata('robots', $this->params->get('robots'));
		}
	}    
    	
}
