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

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

use Einsatzkomponente\Component\Einsatzkomponente\Administrator\Helper\EinsatzkomponenteHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\Database\DatabaseInterface;

/** @var \Einsatzkomponente\Component\Einsatzkomponente\Administrator\View\Einsatzberichte\HtmlView $this */

$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('table.columns')
    ->useScript('multiselect')
    ->useStyle('com_einsatzkomponente.admin');

$user      = Factory::getApplication()->getIdentity();
$userId    = $user->id;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';

if ($saveOrder && !empty($this->items)) {
    $saveOrderingUrl = 'index.php?option=com_einsatzkomponente&task=einsatzberichte.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
    HTMLHelper::_('draggablelist.draggable');
}
$params = ComponentHelper::getParams('com_einsatzkomponente');
?>
<form action="<?php echo Route::_('index.php?option=com_einsatzkomponente&view=einsatzberichte'); ?>" method="post" name="adminForm" id="adminForm">
<div id="j-main-container" class="j-main-container">
    <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

		<table class="table table-striped"  id="einsatzberichtList">
			<thead>
				<tr>
                <?php if (isset($this->items[0]->ordering)): ?>
					<th width="1%" class="nowrap center d-none d-md-table-cell">
						<?php echo HTMLHelper::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
					</th>
                <?php endif; ?>
					<th class="w-1 text-center"><?php echo HTMLHelper::_('grid.checkall'); ?></th>
                <?php if (isset($this->items[0]->state)): ?>
					<th width="1%" class="nowrap center">
						<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
					</th>
                <?php endif; ?>
                
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_DATE1', 'a.date1', $listDirn, $listOrder); ?>
				</th>
<!-- Spalte Alarmierungsart wurde mit Spalte Einsatzart zusammen gelegt			
                <th>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_ALERTING', 'a.alerting', $listDirn, $listOrder); ?>
				</th>
-->		
                <th class='left backend_tickerkat' >
				<?php echo HTMLHelper::_('searchtools.sort',  'Kat', 'a.tickerkat', $listDirn, $listOrder); ?>
				</th>
                <th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_DATA1', 'a.data1', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_ADDRESS', 'a.address', $listDirn, $listOrder); ?>
				</th>
				<th class='left backend_einsatzfoto'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_IMAGE', 'a.image', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_SUMMARY', 'a.summary', $listDirn, $listOrder); ?>
				</th>
<!--				<th class='left'>
				<?php //echo HTMLHelper::_('searchtools.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_DEPARTMENT', 'a.department', $listDirn, $listOrder); ?>
				</th>
-->				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_ZUGRIFFE', 'a.counter', $listDirn, $listOrder); ?>
				</th>
                
            	<?php if ($params->get('gmap_action','0')) : ?>
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  '<small>GMap</small>', 'a.gmap', $listDirn, $listOrder); ?>
				</th>
 				<?php  endif; ?>
                
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_CREATEDATE', 'a.createdate', $listDirn, $listOrder); ?>
				</th>
				
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_UPDATEDATE', 'a.updatedate', $listDirn, $listOrder); ?>
				</th>
                
                <?php if ($this->params->get('info112','0')) : ?>
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  '<small>Info112.net</small>', 'a.notrufticker', $listDirn, $listOrder); ?>
				</th>
                <?php endif; ?>

				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_AUSWAHLORGA', 'a.auswahl_orga', $listDirn, $listOrder); ?>
				</th>
	<!--			<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_CREATED_BY', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_EINSATZKOMPONENTE_EINSATZBERICHTE_MODIFIED_BY', 'a.modified_by', $listDirn, $listOrder); ?>
				</th>
				-->
                    
                <?php if (isset($this->items[0]->id)): ?>
					<th width="1%" class="nowrap center d-none d-md-table-cell">
						<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
                <?php endif; ?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="10">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody <?php if ($saveOrder) : ?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>" data-nested="false"<?php endif; ?>>
			<?php foreach ($this->items as $i => $item) :
				$ordering   = ($listOrder == 'a.ordering');
                $canCreate	= $user->authorise('core.create',		'com_einsatzkomponente');
                $canEdit	= $user->authorise('core.edit',			'com_einsatzkomponente');
                $canCheckin	= $user->authorise('core.manage',		'com_einsatzkomponente');
                $canChange	= $user->authorise('core.edit.state',	'com_einsatzkomponente');
				?>
				<tr class="row<?php echo $i % 2; ?>" data-draggable-group="1">
                    
                <?php if (isset($this->items[0]->ordering)): ?>
					<td class="order nowrap center d-none d-md-table-cell">
					<?php if ($canChange) :
						$disableClassName = '';
						$disabledLabel	  = '';
						if (!$saveOrder) :
							$disabledLabel    = Text::_('JORDERINGDISABLED');
							$disableClassName = 'inactive tip-top';
						endif; ?>
						<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
							<span class="icon-ellipsis-v" aria-hidden="true"></span>
						</span>
						<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
					<?php else : ?>
						<span class="sortable-handler inactive" >
							<span class="icon-ellipsis-v" aria-hidden="true"></span>
						</span>
					<?php endif; ?>
					</td>
                <?php endif; ?>
					<td class="center ">
					<?php $curTime = strtotime((string) $item->date1);?>
					<?php echo '<small>#'.EinsatzkomponenteHelper::ermittle_einsatz_nummer($curTime,$item->data1_id).'/'.date('Y', $curTime).'</small>';?>						
					<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
						
					</td>
                <?php if (isset($this->items[0]->state)): ?>
					<td class="center">
						<?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'einsatzberichte.', $canChange, 'cb'); ?>
					</td>
                <?php endif; ?>
				<td>
					<?php echo $item->date1; ?>
				</td>
				
                <?php // Get Einsatzkategorie
				     $database = Factory::getContainer()->get(DatabaseInterface::class);
                     $query = 'SELECT * FROM #__eiko_tickerkat WHERE title = "'.$item->tickerkat.'" LIMIT 1 ' ;
                     $database->setQuery( $query );
                     $kat = $database->loadObject();	
				?>
				<td class='backend_tickerkat'>
					<?php echo '<img src="../'.$kat->image.'" width="32" height="100%" title="'.$kat->title.'" />'; ?>
				</td>
				
				<td>
                <?php // Get Image of Alarmierungsart
				     $database = Factory::getContainer()->get(DatabaseInterface::class);
                     $query = 'SELECT * FROM #__eiko_alarmierungsarten WHERE title = "'.$item->alerting.'" LIMIT 1 ' ;
                     $database->setQuery( $query );
                     $alerting_image = $database->loadObject();	
				?>
                <?php // Get color of Einsatzart
				     $database = Factory::getContainer()->get(DatabaseInterface::class);
                     $query = 'SELECT * FROM #__eiko_einsatzarten WHERE title = "'.$item->data1.'" LIMIT 1 ' ;
                     $database->setQuery( $query );
                     $data1 = $database->loadObject();	
				?>
				<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'einsatzberichte.', $canCheckin); ?>
				<?php endif; ?>
				
				
					<div style="border-left:6px solid;border-color:<?php echo $data1->marker;?>;padding-left:3px;">
						<?php if ($canEdit) : ?>
						<a href="<?php echo Route::_('index.php?option=com_einsatzkomponente&task=einsatzbericht.edit&id='.(int) $item->id); ?>">
						<?php endif; ?>
					<?php echo '<b>'.$data1->title.'</b>'; ?>
						<?php if ($canEdit) : ?>
						</a>
						<?php endif; ?>
					</div>
				      <?php 
						echo '<div style="padding-top:5px;">';
						if (isset($alerting_image->image)) :
						echo '<img src="../'.$alerting_image->image.'" class="backend_alerting_style" title ="'.$alerting_image->title.'" />';
						endif;
						if (isset($data1->list_icon)) :
						echo '&nbsp;<img src="../'.$data1->list_icon.'" class="backend_data_style" title ="'.$data1->title.'" />';
						endif;
						if (isset($kat->image)) :
						echo '&nbsp;<img src="../'.$kat->image.'" class="backend_kat_style" title="'.$kat->title.'" />';
						endif;
							if ($item->image):
							echo '&nbsp;<img src="../'.$item->image.'" class="backend_foto_style" title="'.$item->image.'"/>';
							endif;
						echo '</div>';?>
                    
                </td>
				<td>
					<?php echo $this->escape($item->address); ?>
				</td>
				<td class='backend_einsatzfoto'>
					<?php echo '<span style="float:left;"> <img src="../'.$item->image.'" width="30" height="100%" title="'.$item->image.'"/></span>';?>
				</td>
				<td>
					<?php echo $item->summary; ?>
				</td>
<!--				<td>
					<?php //echo $item->department; ?>
				</td>
-->				<td>
					<?php echo '<span class="badge">'.$item->counter.'</span>'; ?>
				</td>
                
            	<?php if ($params->get('gmap_action','0')) : ?>
                <?php
			$gmap = $item->gmap;
			
			if ($gmap == '1')
			{
			echo '<td align="center" nowrap="nowrap"><img src="../administrator/media/com_einsatzkomponente/admin/images/ok.png" width="24" height="24" /></td>';
			}
            
            if ($gmap == '0')
            {
			echo '<td align="center" nowrap="nowrap"><img src="../administrator/media/com_einsatzkomponente/admin/images/error.png" width="24" height="24" /></td>';
			}
			?>
 				<?php  endif; ?>
                
				<td>
					<?php echo '<span>'.$item->createdate.'</span><br/><span style="color:#aaaaaa;">'.$item->created_by.'</span>'; ?>
				</td>
				<td>
					<?php echo '<span>'.$item->updatedate.'</span><br/><span style="color:#aaaaaa;">'.$item->modified_by.'</span>'; ?>
				</td>  
                <?php 
// ----------------  info112.net  ----------------------------------------
			if ($this->params->get('info112','0')) : 
			$notrufticker = $item->notrufticker;
			
			if ($notrufticker == '1')
			{
			echo '<td align="center" nowrap="nowrap"><a class="link" id="link" href="'.Route::_('index.php?option=com_einsatzkomponente').'&view=einsatzberichte&tickerID2='.$item->id.'"><img src="../administrator/media/com_einsatzkomponente/admin/images/send.png" width="32" height="32" /></a></td>';
			}
			
			
			if ($notrufticker == '2')
			{
			echo '<td align="center" nowrap="nowrap"><img src="../administrator/media/com_einsatzkomponente/admin/images/ok.png" width="24" height="24" /></td>';
			}
            
            if ($notrufticker == '0')
            {
			echo '<td align="center" nowrap="nowrap"><img src="../administrator/media/com_einsatzkomponente/admin/images/error.png" width="24" height="24" /></td>';
			}
			endif;
// ----------------  info112.net  ENDE ----------------------------------------
			?>
            <td>
					<?php echo $item->auswahl_orga; ?>
				</td>
				
				<!--
				<td>
					<?php echo $item->created_by; ?>
				</td>
				<td>
					<?php echo $item->modified_by; ?>
				</td>
				-->
				
                <?php if (isset($this->items[0]->id)): ?>
					<td class="center d-none d-md-table-cell">
						<?php echo (int) $item->id; ?>
					</td>
                <?php endif; ?>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
</form>        
		
