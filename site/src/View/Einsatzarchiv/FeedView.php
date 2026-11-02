<?php

/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Einsatzkomponente\Component\Einsatzkomponente\Site\View\Einsatzarchiv;

defined('_JEXEC') or die;

use Joomla\CMS\Document\Feed\FeedItem;
use Einsatzkomponente\Component\Einsatzkomponente\Administrator\Helper\EinsatzkomponenteHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

// No direct access

/**
 * View class for a list of Einsatzkomponente.
 */
class FeedView extends BaseHtmlView {

    protected $items;
    protected $pagination;
    protected $state;
    protected $params;
    protected $version;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $app = Factory::getApplication();

        $this->state = $this->getModel()->getState();
        $this->items = $this->getModel()->getItems();
        $this->pagination = $this->getModel()->getPagination();
        $this->params = $app->getParams('com_einsatzkomponente');
        $this->filterForm = $this->getModel()->getFilterForm();
		$this->activeFilters = $this->getModel()->getActiveFilters();

		
		$layout_detail = $this->params->get('layout_detail',''); // Detailbericht Layout
		$this->layout_detail_link = '';  
		if ($layout_detail) : $this->layout_detail_link = '&layout='.$layout_detail;  endif; // Detailbericht Layout 'default' ?

		
		$app	= Factory::getApplication();
		$menus	= $app->getMenu();
		$title	= null;
		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		$this->getDocument()->link = Route::_('index.php?option=com_einsatzkomponente&view=einsatzarchiv&Itemid='.$menu->id);
		$i=0;
		foreach ( $this->items as $item )
		{
			if ($this->params->get('rss_items','2') == $i ) : break;endif;
			$i++;
			
			
			//$item->auswahl_orga = str_replace(",", " +++ ", $this->auswahl_orga);
			$summary = $this->escape( $item->summary );
			$title = html_entity_decode( $summary );
			$desc = strip_tags( $item->desc);
			$desc = (strlen($desc) > $this->params->get('rss_chars','1000')) ? substr($desc,0,strrpos(substr($desc,0,$this->params->get('rss_chars','1000')+1),' ')).' ...' : $desc;
			$nr = EinsatzkomponenteHelper::ermittle_einsatz_nummer($item->date1,$item->data1_id);
			// url link to article
			// & used instead of &amp; as this is converted by feed creator
			$link = Route::_('index.php?option=com_einsatzkomponente&view=einsatzbericht'.$this->layout_detail_link.'&id='.$item->id);

			//$auswahl_orga=  implode(',',$this->auswahl_orga); 
			$item->auswahl_orga = str_replace(",", " +++ ", $item->auswahl_orga);

			// strip html from feed item description text
			/*$description	= ($params->get('feed_summary', 0) ? $item->introtext.$item->fulltext : $item->introtext);
			$author			= $item->created_by_alias ? $item->created_by_alias : $item->author;
*/
			// load individual item creator class
			$rss_item = new FeedItem();
			$rss_item->title = "+++ Einsatz Nr: " . $nr . " - " . $title . " +++";
			$rss_item->link 		= $link;

			$rss_item->description 	.= '<table>';
			
			if ($item->date1 >1 and $this->params->get('display_rss_alerttime','0')) : 
			$rss_item->description 	.= '<tr><td><b>Alarmierung am</b>: '.date('d.m.Y', $item->date1).' um '.date('H:i', $item->date1).' Uhr</td></tr>';
			endif;


			if ($desc) :
			$rss_item->description 	.= '<tr><td>'.$desc.'</td></tr>';
			endif;

			if ($item->auswahl_orga and $this->params->get('display_rss_orgas','1')) :
			$rss_item->description 	.= '<tr><td><b>Einsatzkräfte</b>: +++ '.$item->auswahl_orga.' +++</td></tr>';
			endif;
			
			if ($item->vehicles and $this->params->get('display_rss_vehicles','0')) :
			$rss_item->description 	.= '<tr><td><b>Einsatzfahrzeuge</b>: +++ '.$item->vehicles.' +++</td></tr>';
			endif;
			
			if ($item->people and $this->params->get('display_rss_people','0')) :
			$rss_item->description 	.= '<tr><td><b>Mannschaftsstärke</b>: '.$item->people.'</td></tr>';
			endif;
			
			if ($item->date3 >1 and $this->params->get('display_rss_time','0')) : 
			$einsatzdauer = EinsatzkomponenteHelper::getEinsatzdauer(date('d.m.Y H:i', $item->date1),$item->date3);
			$rss_item->description 	.= '<tr><td><b>Einsatzdauer</b>: '.$einsatzdauer.'</td></tr>';
			endif;

			if ($this->params->get('display_rss_image','0')) :
			if ($item->image) :
			$rss_item->description 	.= '<tr><td><img src="'.Uri::base().$item->image.'" width="'.$this->params->get('rss_image_width','250px').'" height="'.$this->params->get('rss_image_height','').' "/></td></tr>';
			endif;
			endif;
			
			
		    $rss_item->date = date('d.m.Y H:i', $item->date1);
			
			$rss_item->description 	.= '</table>';
			
			// loads item info into rss array
			$this->getDocument()->addItem( $rss_item );
		}


		

        //parent::display($tpl);
    }


}