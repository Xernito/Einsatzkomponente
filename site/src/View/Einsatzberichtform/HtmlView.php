<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Einsatzkomponente\Component\Einsatzkomponente\Site\View\Einsatzberichtform;

defined('_JEXEC') or die;

use Einsatzkomponente\Component\Einsatzkomponente\Administrator\Helper\EinsatzkomponenteHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
/**
 * View to edit
 */
class HtmlView extends BaseHtmlView {
    protected $state;
    protected $item;
    protected $form;
    protected $params;
	protected $copy;
    /**
     * Display the view
     */
    public function display($tpl = null) {
        
		$app	= Factory::getApplication();
        $user		= Factory::getApplication()->getIdentity();
       
	    $this->copy  = Factory::getApplication()->getInput()->getInt('copy','0');

        $this->state = $this->getModel()->getState();
        $this->item = $this->getModel()->getData();
        $this->params = $app->getParams('com_einsatzkomponente');
   		$this->form		= $this->getModel()->getForm();
		$this->gmap_config = EinsatzkomponenteHelper::load_gmap_config(); // GMap-Config aus helper laden 
 
 		$document = Factory::getApplication()->getDocument();
		
		// Bootstrap laden
		
		if ($this->params->get('display_home_bootstrap','0') == '1') :
		endif;
		if ($this->params->get('display_home_bootstrap','0') == '2') :
		$document->addStyleSheet('media/com_einsatzkomponente/css/bootstrap/bootstrap.min.css');
		$document->addStyleSheet('media/com_einsatzkomponente/css/bootstrap/bootstrap-responsive.min.css');
		endif;

		$document->getWebAssetManager()->useStyle('com_einsatzkomponente.einsatzkomponente');
		if (trim((string) $this->params->get('edit_css','')) !== '') {
			$document->getWebAssetManager()->addInlineStyle($this->params->get('edit_css',''));
		} 

		
		// Enter-Taste abstellen
		echo '<script type="text/javascript">

function stopRKey(evt) {
  var evt = (evt) ? evt : ((event) ? event : null);
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
  if ((evt.keyCode == 13) && (node.type=="text"))  {return false;}
}

document.onkeypress = stopRKey;

</script> ';

        
   //     if($this->getLayout() == 'edit') {
    //         $authorised = $user->authorise('core.edit', 'com_einsatzkomponente');
    //         if ($authorised !== true) {
   //            throw new \Exception(Text::_('ALERTNOAUTHOR'));
   //         }
  //     }
        
 
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
