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

/** @var \Einsatzkomponente\Component\Einsatzkomponente\Administrator\View\Einsatzbildmanager\HtmlView $this */

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
    $saveOrderingUrl = 'index.php?option=com_einsatzkomponente&task=einsatzbildmanager.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
    HTMLHelper::_('draggablelist.draggable');
}
?>
<form action="<?php echo Route::_('index.php?option=com_einsatzkomponente&view=einsatzbildmanager'); ?>" method="post" name="adminForm" id="adminForm">
<div id="j-main-container" class="j-main-container">
    <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

		<table class="table table-striped"  id="einsatzbilderbearbeitenList">
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
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_EINSATZKOMPONENTE_EINSATZBILDMANAGER_IMAGE', 'a.image', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_EINSATZKOMPONENTE_EINSATZBILDMANAGER_THUMB', 'a.thumb', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_EINSATZKOMPONENTE_EINSATZBILDMANAGER_REPORT_ID', 'a.report_id', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'Bild-Info', 'a.comment', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo HTMLHelper::_('searchtools.sort',  'COM_EINSATZKOMPONENTE_EINSATZBILDMANAGER_CREATED_BY', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
                    
                    
                <?php if (isset($this->items[0]->id)): ?>
					<th width="1%" class="nowrap center d-none d-md-table-cell">
						<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
                <?php endif; ?>
				</tr>
			</thead>
			<tfoot>
                <?php 
                if(isset($this->items[0])){
                    $colspan = count(get_object_vars($this->items[0]));
                }
                else{
                    $colspan = 10;
                }
            ?>
			<tr>
				<td colspan="<?php echo $colspan ?>">
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
					<td class="center d-none d-md-table-cell">
						<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
					</td>
                <?php if (isset($this->items[0]->state)): ?>
					<td class="center">
						<?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'einsatzbildmanager.', $canChange, 'cb'); ?>
					</td>
                <?php endif; ?>
                  
 				<td>
                <?php if ($canEdit) : ?>
					<a href="<?php echo Route::_('index.php?option=com_einsatzkomponente&task=einsatzbilderbearbeiten.edit&id='.(int) $item->id); ?>">
                   <?php echo '<span style="float:left;">'.$item->image.'</span>';?>
                   </a>
					<?php else : ?>
                   <?php echo '<span style="float:left;">'.$item->image.'</span>';?>
                  <?php endif; ?>
				</td>
 				<td>
                <?php if ($canEdit) : ?>
					<a href="<?php echo Route::_('index.php?option=com_einsatzkomponente&task=einsatzbilderbearbeiten.edit&id='.(int) $item->id); ?>">
				 	<?php echo '<span style="float:left;"><img src="../'.$item->thumb.'" width="100" height="100%" /></span>';?>
                   </a>
         		<?php else : ?>
				 <?php echo '<span style="float:left;"><img src="../'.$item->thumb.'" width="100" height="100%" /></span>';?>
                  <?php endif; ?>
				</td>
				<td>
					<?php echo $item->report_id; ?>
				</td>
				<td>
					<?php echo $item->comment; ?>
				</td>
				<td>
					<?php echo $item->created_by; ?>
				</td>
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
		
