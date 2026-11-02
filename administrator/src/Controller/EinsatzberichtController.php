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

use Einsatzkomponente\Component\Einsatzkomponente\Administrator\Helper\EinsatzkomponenteHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Uri\Uri;
use Joomla\Database\DatabaseInterface;
/**
 * Einsatzbericht controller class.
 */
class EinsatzberichtController extends FormController
{
    public function __construct($config = [], $factory = null, $app = null, $input = null)
    {
        $this->view_list = 'einsatzberichte';
        parent::__construct($config, $factory, $app, $input);
    }
    public function pdf() {
    	// Check for request forgeries
	$this->checkToken();
	
    	$cid = Factory::getApplication()->getInput()->get('id', array(), 'array');
    	if (!is_array($cid) || count($cid) < 1)
	{
	    Factory::getApplication()->enqueueMessage(Text::_($this->text_prefix . '_NO_ITEM_SELECTED'), 'error');
	}
	else
	{
    	    $msg = EinsatzkomponenteHelper::pdf($cid);
	    $this->setRedirect('index.php?option=com_einsatzkomponente&view=einsatzbericht&layout=edit&id='.$cid[0], $msg);
	}
    }
 
    	public function save($key = null, $urlVar = null)
    {
		// Check for request forgeries
		$this->checkToken();

		// Get items to remove from the request.
		$send = 'false';
		$cid = Factory::getApplication()->getInput()->get('id','0');
		$params = ComponentHelper::getParams('com_einsatzkomponente');

		$result = parent::save($key, $urlVar);

		if ($result) :
		
		// Wasserzeichen speichern
		$input = Factory::getApplication()->getInput();
		// Get the form data
		$formData = new \Joomla\Input\Input($input->get('jform', '', 'array'));
		// Get any data being able to use default values
		$watermark_image = $formData->getString('watermark_image');

		$params = ComponentHelper::getParams('com_einsatzkomponente');
		// Set new value of param(s)
		$params->set('watermark_image', $watermark_image);

		// Save the parameters
		$componentid = ComponentHelper::getComponent('com_einsatzkomponente')->id;
		$table = new \Joomla\CMS\Table\Extension(Factory::getContainer()->get(DatabaseInterface::class));
		$table->load($componentid);
		$table->bind(array('params' => $params->toString()));

		// check for error
		if (!$table->check()) {
			echo $table->getError();
			return false;
		}
		// Save to database
		if (!$table->store()) {
			echo $table->getError();
			return false;
		}
		// Wasserzeichen speichern  ENDE
		
		// Bilder upload
		$files        = Factory::getApplication()->getInput()->files->get('data', '', 'array');
		
		if (!empty($files[0]['name'])) : 
		if (!$cid) :
		$db = Factory::getContainer()->get(DatabaseInterface::class);
		$query = "SELECT id FROM #__eiko_einsatzberichte ORDER BY id DESC LIMIT 1";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$cid      = $rows[0]->id; 
		endif;
		require_once JPATH_SITE.'/administrator/components/com_einsatzkomponente/helpers/upload.php'; // Helper-class laden
			endif;		

		$automail_off = $formData->getString('automail_off');

				if ( $params->get('send_mail_auto', '0') ): 
				if ( !$automail_off ): 
		if (!$cid) :
		$db = Factory::getContainer()->get(DatabaseInterface::class);
		$query = "SELECT id FROM #__eiko_einsatzberichte ORDER BY id DESC LIMIT 1";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$cid      = $rows[0]->id;
		$send = sendMail_auto($cid,'neuer Bericht: ');
		else:
		$send = sendMail_auto($cid,'Update: ');
		endif;
		endif;
		endif;
	endif;
    //print_r ($send);break;

		return $result;
    }
}
	    function sendMail_auto($cid,$status) {

		
		$input = Factory::getApplication()->getInput();
		// Get the form data
		$formData = new \Joomla\Input\Input($input->get('jform', '', 'array'));
		// Get any data being able to use default values
		$emailtext = $formData->getString('emailtext');

		//$model = $this->getModel();
		$params = ComponentHelper::getParams('com_einsatzkomponente');
		$user = Factory::getApplication()->getIdentity();
		$query = 'SELECT * FROM #__eiko_einsatzberichte WHERE id = "'.$cid.'" LIMIT 1';
		$db = Factory::getContainer()->get(DatabaseInterface::class);
		$db->setQuery($query);
		$result = $db->loadObjectList();
	
		$mailer = Factory::getMailer();
		$config = Factory::getConfig();
		
		$sender = array( 
    	$user->email,
    	$user->name );
		
		$mailer->setSender($sender);
		
		$user = Factory::getApplication()->getIdentity();
		$recipient = $params->get('mail_empfaenger_auto',$user->email);
		
		$recipient 	 = explode( ',', $recipient);
		
					$data = array();
					foreach(explode(',',$result[0]->auswahl_orga) as $value):
						$db = Factory::getContainer()->get(DatabaseInterface::class);
						$query	= $db->getQuery(true);
						$query
							->select('name')
							->from('#__eiko_organisationen')
							->where('id = "' .$value.'"');
						$db->setQuery($query);
						$results = $db->loadObjectList();
						if(count($results)){
							$data[] = ''.$results[0]->name.''; 
						}
					endforeach;
					$auswahl_orga=  implode(',',$data); 

					$orga		 = explode( ',', $auswahl_orga);
		$orgas 		 = str_replace(",", " +++ ", $auswahl_orga);
 
		$mailer->addRecipient($recipient);
		
		$mailer->setSubject($status.''.$orga[0].'  +++ '.$result[0]->summary.' +++');
		
		$db = Factory::getContainer()->get(DatabaseInterface::class);
		$query = $db->getQuery(true);
					$query
						->select('*')
						->from('#__eiko_tickerkat')
						->where('id = "' .$result[0]->tickerkat.'"  AND state = "1" ');
					$db->setQuery($query);
					$kat = $db->loadObject();
		
		$link = Route::_( Uri::root() . 'index.php?option=com_einsatzkomponente&view=einsatzbericht&id='.$result[0]->id.'&Itemid='.$params->get('homelink','')); 
		
		$body   = '';
			if ($emailtext) :
			$body   .= $emailtext.'<hr>';
			endif;
		$body	. '<h2>+++ '.$result[0]->summary.' +++</h2>';
		if ($params->get('send_mail_kat','0')) :	
		$body   .= '<h4>'.Text::_($kat->title).'</h4>';
		endif;
		if ($params->get('send_mail_orga','0')) :	
		$body   .= '<span><b>Eingesetzte Kräfte:</b> '.$orgas.'</span>';
		endif;
		$body   .= '<div>';
		if ($params->get('send_mail_desc','0')) :	
		if ($result[0]->desc) :	
    	$body   .= '<p>'.$result[0]->desc.'</p>';
		else:
    	$body   .= '<p>Ein ausführlicher Bericht ist zur Zeit noch nicht vorhanden.</p>';
		endif;
		endif;
		if ($params->get('send_mail_link','0')) :	
    	$body   .= '<p><a href="'.$link.'" target="_blank">Link zur Homepage</a></p>';
		endif;
		if ($result[0]->image) :	
		if ($params->get('send_mail_image','0')) :	
		$body   .= '<img src="'.Uri::root().$result[0]->image.'" style="margin-left:10px;float:right;height:50%;" alt="Einsatzbild"/>';
		endif;
		endif;
		$body   .= '</div>';
		

		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setBody($body);
		// Optionally add embedded image
		//$mailer->AddEmbeddedImage( JPATH_ADMINISTRATOR . '/components/com_einsatzkomponente'.'/assets/logo128.jpg', 'logo_id', 'logo.jpg', 'base64', 'image/jpeg' );
		
		$send = $mailer->Send();
        return 'gesendet'; 
    }
	
