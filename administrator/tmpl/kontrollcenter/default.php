<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */
// no direct access
defined('_JEXEC') or die;

use Joomla\Database\DatabaseInterface;
use Einsatzkomponente\Component\Einsatzkomponente\Administrator\Helper\EinsatzkomponenteHelper;
use Joomla\CMS\Version;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$val= EinsatzkomponenteHelper::getValidation();

// Import CSS
$document = Factory::getApplication()->getDocument();
$document->getWebAssetManager()->useStyle('com_einsatzkomponente.admin');
// Versions-Nummer 
$db = Factory::getContainer()->get(DatabaseInterface::class);
$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_einsatzkomponente"');
$params = json_decode( $db->loadResult(), true );
$user	= Factory::getApplication()->getIdentity();
$userId	= $user->get('id');
?>

<?php
if (!empty($this->extra_sidebar)) {
    $this->sidebar .= $this->extra_sidebar;
}
?>
<form action="<?php echo Route::_('index.php?option=com_einsatzkomponente&view=kontrollcenter'); ?>" method="post" name="adminForm" id="adminForm">
<?php if(!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="col-md-2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="col-md-10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

							
		<div id="filter-bar" class="btn-toolbar"></div> 
               
        
			  <div class="btn-group btn-group-justified">
	    					<a class="btn" href="index.php?option=com_einsatzkomponente&view=einsatzberichte">
		    				<img style="width:32px;height:32px;" alt="<?php echo Text::_('COM_EINSATZKOMPONENTE_TITLE_EINSATZBERICHTE'); ?>" src="<?php echo \Joomla\CMS\Uri\Uri::root(true); ?>/media/com_einsatzkomponente/admin/images/menu/liste.png" /><br/>
		    				<span style="font-size:11px;"><?php echo Text::_('COM_EINSATZKOMPONENTE_TITLE_EINSATZBERICHTE'); ?></span>
	    					</a>
							
	    					<a class="btn" href="index.php?option=com_einsatzkomponente&view=kategorien">
		    				<img style="width:32px;height:32px;" alt="<?php echo Text::_('COM_EINSATZKOMPONENTE_TITLE_KATEGORIEN'); ?>" src="<?php echo \Joomla\CMS\Uri\Uri::root(true); ?>/media/com_einsatzkomponente/admin/images/menu/einsatzarten.png" /><br/>
		    				<span style="font-size:11px;"><?php echo Text::_('COM_EINSATZKOMPONENTE_TITLE_KATEGORIEN'); ?></span>
	    					</a>   						
						
	    					<a class="btn" href="index.php?option=com_einsatzkomponente&view=einsatzarten">
		    				<img style="width:32px;height:32px;" alt="<?php echo Text::_('COM_EINSATZKOMPONENTE_TITLE_EINSATZARTEN'); ?>" src="<?php echo \Joomla\CMS\Uri\Uri::root(true); ?>/media/com_einsatzkomponente/admin/images/menu/einsatzarten.png" /><br/>
		    				<span style="font-size:11px;"><?php echo Text::_('COM_EINSATZKOMPONENTE_TITLE_EINSATZARTEN'); ?></span>
	    					</a>    						
						
	    					<a class="btn" href="index.php?option=com_einsatzkomponente&view=organisationen">
		    				<img style="width:32px;height:32px;" alt="<?php echo Text::_('COM_EINSATZKOMPONENTE_TITLE_ORGANISATIONEN'); ?>" src="<?php echo \Joomla\CMS\Uri\Uri::root(true); ?>/media/com_einsatzkomponente/admin/images/menu/organisationen.png" /><br/>
		    				<span style="font-size:11px;"><?php echo Text::_('COM_EINSATZKOMPONENTE_TITLE_ORGANISATIONEN'); ?></span>
	    					</a>    						
						
	    					<a class="btn" href="index.php?option=com_einsatzkomponente&view=alarmierungsarten">
		    				<img style="width:32px;height:32px;" alt="<?php echo Text::_('COM_EINSATZKOMPONENTE_TITLE_ALARMIERUNGSARTEN'); ?>" src="<?php echo \Joomla\CMS\Uri\Uri::root(true); ?>/media/com_einsatzkomponente/admin/images/menu/alarmierungsarten.png" /><br/>
		    				<span style="font-size:11px;"><?php echo Text::_('COM_EINSATZKOMPONENTE_TITLE_ALARMIERUNGSARTEN'); ?></span>
	    					</a>    						
						
	    					<a class="btn" href="index.php?option=com_einsatzkomponente&view=einsatzfahrzeuge">
		    				<img style="width:32px;height:32px;" alt="<?php echo Text::_('COM_EINSATZKOMPONENTE_TITLE_EINSATZFAHRZEUGE'); ?>" src="<?php echo \Joomla\CMS\Uri\Uri::root(true); ?>/media/com_einsatzkomponente/admin/images/menu/einsatzfahrzeuge.png" /><br/>
		    				<span style="font-size:11px;"><?php echo Text::_('COM_EINSATZKOMPONENTE_TITLE_EINSATZFAHRZEUGE'); ?></span>
	    					</a>
    						
	    					<!--<a class="btn" href="index.php?option=com_einsatzkomponente&view=beispiel">
		    				<img  style="width:32px;height:32px;" alt="<?php echo Text::_('COM_EINSATZKOMPONENTE_TITLE_BEISPIEL'); ?>" src="<?php echo \Joomla\CMS\Uri\Uri::root(true); ?>/media/com_einsatzkomponente/admin/images/menu/beispiel.png" /><br/>
		    				<span style="font-size:11px;"><?php echo Text::_('COM_EINSATZKOMPONENTE_TITLE_BEISPIEL'); ?></span>
	    					</a>-->
    					
	    					<a class="btn" href="index.php?option=com_einsatzkomponente&view=einsatzbildmanager">
		    				<img style="width:32px;height:32px;" alt="<?php echo Text::_('COM_EINSATZKOMPONENTE_TITLE_EINSATZBILDMANAGER'); ?>" src="<?php echo \Joomla\CMS\Uri\Uri::root(true); ?>/media/com_einsatzkomponente/admin/images/menu/einsatzbildmanager.png" /><br/>
		    				<span style="font-size:11px;"><?php echo Text::_('COM_EINSATZKOMPONENTE_TITLE_EINSATZBILDMANAGER'); ?></span>
	    					</a>

	    					<a class="btn" href="index.php?option=com_config&view=component&component=com_einsatzkomponente">
		    				<img style="width:32px;height:32px;" alt="<?php echo Text::_('COM_EINSATZKOMPONENTE_OPTIONS'); ?>" src="<?php echo \Joomla\CMS\Uri\Uri::root(true); ?>/media/com_einsatzkomponente/admin/images/menu/einstellungen.png" /><br/>
		    				<span style="font-size:11px;"><?php echo Text::_('COM_EINSATZKOMPONENTE_OPTIONS'); ?></span>
	    					</a>

				</div>
					
					
			<table class="table"> 
			<thead>
				<tr>
					<th>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>

<div class="col-md-4">
<div class="alert alert-info" style=" float:left;">
<a target="_blank" href="https://www.einsatzkomponente.de/index.php"><img src="<?php echo Uri::root(true); ?>/media/com_einsatzkomponente/admin/images/komponentenbanner.jpg" style="float:left; margin-right:20px; padding-right:20px;"/></a>
<span class="label label-important" style="padding: 5px 5px 5px 5px;font-size:larger;"><?php echo Text::_('COM_EINSATZKOMPONENTE_KONTROLLCENTER_PAYPAL_1');?></span><br/><br/> 
<span class="label label-important" style="padding: 5px 5px 5px 5px;font-size:larger;"><?php echo 'Bzw. Werde Premium-User';?></span><br/><br/>
<?php echo Text::_('COM_EINSATZKOMPONENTE_KONTROLLCENTER_PAYPAL_2');?>
<div class="alert alert-warning" style=" float:left;margin-top:10px;">PS: Ihr dürft auch gerne per PayPal <b>eine kleine monatliche Spende</b> einrichten.</div>
<small>PayPal-Email: <?php echo 'ralf.meyer@mail.de';?></small><br />

<p><a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9HDFKVJSKSEFY"><span style="float:right;"><?php echo Text::_('COM_EINSATZKOMPONENTE_KONTROLLCENTER_SPENDEN_UEBER_PAYPAL');?>: <img border=0  src="https://www.paypalobjects.com/de_DE/DE/i/btn/btn_donateCC_LG.gif" /></span></a>
<p><small><span style="float:left;"></br>Alternativ können Sie die Kontodaten per <a href="mailto:validate@einsatzkomponente.de?Subject=Spende%20Einsatzkomponente%20J3.x" target="_top">Email </a>anfordern.</span></small></p>
</p>

</div>
</div>
<div class="col-md-5">
					<div class="well well-small" style=" float:left;">
						<div class="center">
							<?php echo '<h4>'.Text::_('COM_EINSATZKOMPONENTE_KONTROLLCENTER_TITLE');?><?php echo '</h4>';?>
						</div>
						<hr class="hr-condensed">
						<dl class="dl-horizontal">
							<dt>Version:</dt>
							<dd><?php echo $params['version'];?>
							<?php if ($val) : ?>
							<?php echo '<span class="label label-success"> ( validiert ) </span>';?>
                            <?php else:?>
							<?php echo '<span class="label label-important"> ( nicht validiert ) </span><br/>siehe Optionen / Info';?>
                            <?php endif;?>
							
								<?php Factory::getApplication()->getDocument()->getWebAssetManager()->useScript('bootstrap.modal'); ?>
								</br>
						<a href="#eiko-changelog" data-bs-toggle="modal"><?php echo 'Changelog'; ?></a>
						<div id="eiko-changelog" class="modal fade" tabindex="-1" aria-hidden="true">
							<div class="modal-dialog modal-lg modal-dialog-scrollable">
								<div class="modal-content">
									<div class="modal-header">
										<h3 class="modal-title">Changelog</h3>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo Text::_('JCLOSE'); ?>"></button>
									</div>
									<div class="modal-body">
										<?php
											echo changelog (JPATH_ADMINISTRATOR . '/components/com_einsatzkomponente'.'/CHANGELOG.php');
										?>
									</div>
								</div>
							</div>
						</div>

                            </dd>
							<hr>
							<dt>Release-Datum:</dt>
							<dd><?php echo $params['creationDate'];?></dd>
							<dt>Autor:</dt>
							<dd><?php echo $params['author'];?></dd>
							<dt>Autor-Email:</dt>
							<dd><?php echo $params['authorEmail'];?></dd>
							<dt>Copyright:</dt>
							<dd><?php echo $params['copyright'];?></dd>
							<dt>Lizenz:</dt>
							<dd>GNU General Public License version 2 or later </dd>
						</dl>
						<hr>
							<b>Premiumfunktionen (nur für Premium-User):</b></br>
							<?php if ($val) : ?>
							<?php echo '<span style="margin-bottom:5px;" class="label label-success">Mehrfachbild-Upload im Frontend-Edit</span></br>';?>
							<?php else:?>
							<?php echo '<span style="margin-bottom:5px;text-decoration: line-through;" class="label label-important">Mehrfach-Bildupload im Frontend-Edit</span></br>';?>
							<?php endif;?>
							<?php if ($this->params->get('eiko')) : ?>
							<?php echo '<span style="margin-bottom:5px;" class="label label-success">Option Ausrüstung</span></br>';?>
							<?php else:?>
							<?php echo '<span style="margin-bottom:5px;text-decoration: line-through;" class="label label-important">Option Ausrüstung</span></br>';?>
							<?php endif;?>
							<hr>
						<b>Informationen:</b></br>
						<a target="_blank" style="margin-bottom:5px;" style="margin-bottom:5px;" class="label label-info" href="https://www.einsatzkomponente.de">Download-Link Webseite</a> 
						<br/>
						<a target="_blank" style="margin-bottom:5px;" class="label label-info" href="https://github.com/veenmeyer/Einsatzkomponente">Link zu GitHub</a>			
					</br>
						<!-- Button to trigger modal -->
						<a href="#myModal" role="button" style="margin-bottom:5px;" class="label label-info" data-bs-toggle="modal">Verfügbare Module ...</a>
     
						<!-- Modal -->
						<div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog"><div class="modal-content">
						<div class="modal-header">
						<h3 id="myModalLabel" class="modal-title">Verfügbare Module</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
						<ul>
						<li><a href="https://einsatzkomponente.de/forum/index.php?thread/1225-mod-eiko-last-f%C3%BCr-j3-x/" target="_blank" class="">mod_eiko_last</a> (Modul zur Anzeige der letzten Einsätze auf einer Modulposition)</li>
						<li><a href="https://einsatzkomponente.de/forum/index.php?thread/1234-mod-eiko-chart-statistik-modul-als-kuchen-f%C3%BCr-j3-x/" target="_blank" class="">mod_eiko_chart</a> (Modul zur Anzeige einer Statistik in Kuchenform auf einer Modulposition)</li>
						<li><a href="https://einsatzkomponente.de/forum/index.php?thread/1228-mod-eiko-statistik-f%C3%BCr-j3-x/" target="_blank" class="">mod_eiko_statistik</a> (Modul zur Anzeige einer Statistik als Balkendiagramm auf einer Modulposition)</li>
						<li><a href="https://einsatzkomponente.de/forum/index.php?thread/1238-mod-eiko-melder-f%C3%BCr-j3-x/" target="_blank" class="">mod_eiko_melder (nur Premium User)</a> (Modul zur Anzeige des letzten Einsatzes auf einen Meldeempfänger)</li>
						<li><a href="https://einsatzkomponente.de/forum/index.php?thread/1235-mod-eiko-einsatzticker-f%C3%BCr-j3-x/" target="_blank" class="">mod_eiko_einsatzticker</a> (Modul zur Anzeige des letzten Einsatzes als Tickerlaufschrift)</li>
						</ul>
						<h4>Mehr Infos dazu auf <a href="https://www.einsatzkomponente.de/" target="_blank" class="">www.einsatzkomponente.de</a></h4>
						</div>
						<div class="modal-footer">
						<button class="btn btn-secondary" data-bs-dismiss="modal">Schliessen</button>
						</div>
						</div></div>
						</div>	
						<hr>
						<b>Info PHP-Funktionen:</b></br>
						
						<?php 
							if( ini_get('allow_url_fopen') ) {
								echo '<span class="label label-success">allow_url_fopen aktiv</span>';
								} else {
								echo '<span class="label label-important">allow_url_fopen deaktiviert</span>';
								}
						?>
				<hr>
						Aktuellste Version: <iframe  frameborder="0" height="30px" width="250px" src="https://www.feuerwehr-veenhusen.de/images/einsatzkomponenteJ30/index.html" scrolling="no"></iframe>
						
</div> 
          

				
						
					</td>
                    
               </tr>
               
                
                <tr>
               		 <td>
						<div class="alert alert-block alert-info">
						<button class="close" data-bs-dismiss="alert" type="button">×</button>
						<p> </p>
						<h4 style="margin-bottom:5px;">Weitere Links</h4>
						<ul>
						<li>
						<a target="_blank" href="https://einsatzkomponente.de" style="text-decoration:underline">Supportforum für die Einsatzkomponente</a>
						</li>
						<li>
						<a target="_blank" href="https://demo.einsatzkomponente.de" style="text-decoration:underline">Testseite für die Einsatzkomponente V3.x für J3</a>
						</li>
						<li>
						<a target="_blank" href="https://www.feuerwehr-veenhusen.de" style="text-decoration:underline">Freiwillige Feuerwehr Veenhusen </a>
						</li>
						</ul>
						</div>
                  </td>
             </tr>
                
            </tbody>
			<tfoot>
				<tr>
					<td colspan="10">
						<?php echo 'Copyright (C) 2019 by Ralf Meyer. All rights reserved.
 *  GNU General Public License version 2 or later'; ?>
					</td>
				</tr>
			</tfoot>
            
		</table>
		<input type="hidden" name="task" value="" />
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
</form>     
	
<?php	
	function changelog($file, $onlyLast = false)
	{
		$ret = '';

		$lines = @file($file);

		if(empty($lines)) return $ret;

		array_shift($lines);

		foreach($lines as $line)
		{
			$line = trim($line);

			if(empty($line)) continue;

			$type = substr($line,0,1);

			switch($type)
			{
				case '=':
					break;
				case '-':
					$ret .= '<li><span style="font-size:8pt;color:#ff0000;">Removed:</span> '.substr($line, 1).'</li>';
					break;
				case '+':
					$ret .= '<li><span style="font-size:8pt;color:#ff0000;">Added:</span> '.substr($line, 1)."</li>";
					break;
				case '#':
					$ret .= '<li><span style="font-size:8pt;color:#00e600;">Bugfix:</span> '.substr($line, 1)."</li>";
					break;

				default:

					$ret .= $line;
					break;
			}
		}

		return $ret;
	}
	
?>