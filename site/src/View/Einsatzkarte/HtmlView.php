<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Einsatzkomponente\Component\Einsatzkomponente\Site\View\Einsatzkarte;

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
    protected $reports;
    protected $years;
    protected $version;
    protected $einsatzarten;
    protected $layout_detail_link;
    protected $gmap_config;
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
		$this->gmap_config = EinsatzkomponenteHelper::load_gmap_config(); // GMap-Config aus helper laden 
		
		$aktuelles_Datum = getdate(); 
		
		
		//Komponentenversion aus Datenbank lesen
		$this->version 		= EinsatzkomponenteHelper::getVersion (); 

		
		//Einsatzdaten aus der Datenbank holen
		$count = EinsatzkomponenteHelper::count_einsatz_daten_bestimmtes_jahr (''); 
		$this->reports = EinsatzkomponenteHelper::einsatz_daten_bestimmtes_jahr ('','99999','0'); 

		
		
		

		$this->years 				= EinsatzkomponenteHelper::getYear (); // Alle Jahre der Einsatzdaten ermitteln
		$this->einsatzarten 		= EinsatzkomponenteHelper::getEinsatzarten (); // Alle Einsatzarten der Einsatzdaten ermitteln
		$this->organisationen 		= EinsatzkomponenteHelper::getOrganisationen (); // Alle Einsatzarten der Einsatzdaten ermitteln
		

		$layout_detail = $this->params->get('layout_detail',''); // Detailbericht Layout
		$this->layout_detail_link = ''; 
		if ($layout_detail) : $this->layout_detail_link = '&layout='.$layout_detail;  endif; // Detailbericht Layout 'default' ?
		
		$document = Factory::getApplication()->getDocument();
		
        // Import CSS
		$document->getWebAssetManager()->useStyle('com_einsatzkomponente.einsatzkomponente');
		$document->addStyleSheet(\Joomla\CMS\Uri\Uri::root(true) . '/media/com_einsatzkomponente/css/responsive.css');
		
		// Bootstrap laden
		
		if ($this->params->get('display_einsatzkarte_bootstrap','0') == '1') :
		endif;
		if ($this->params->get('display_einsatzkarte_bootstrap','0') == '2') :
		$document->addStyleSheet(\Joomla\CMS\Uri\Uri::root(true) . '/media/com_einsatzkomponente/css/bootstrap/bootstrap.min.css');
		$document->addStyleSheet(\Joomla\CMS\Uri\Uri::root(true) . '/media/com_einsatzkomponente/css/bootstrap/bootstrap-responsive.min.css');
		endif;

		// Import CSS aus Optionen
		if (trim((string) $this->params->get('gmap_css','')) !== '') {
			$document->getWebAssetManager()->addInlineStyle($this->params->get('gmap_css',''));
		} 
		
		
		

		
        if ($this->params->get('display_home_rss','1')) : 
		// RSS-Feed in den Dokumenten-Header einfügen
		$href = 'index.php?option=com_einsatzkomponente&view=einsatzberichte&format=feed&type=rss'; 
		$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0'); 
		$document->addHeadLink( $href, 'alternate', 'rel', $attribs );
		endif;

        
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
