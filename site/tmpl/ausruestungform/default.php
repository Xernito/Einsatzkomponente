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
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

//Load admin language file
$lang = Factory::getApplication()->getLanguage();
$lang->load('com_einsatzkomponente', JPATH_ADMINISTRATOR);
$doc = Factory::getApplication()->getDocument();
$doc->addScript(Uri::base() . '/media/com_einsatzkomponente/js/form.js');

// Import CSS
$document = Factory::getApplication()->getDocument();
$document->getWebAssetManager()->useStyle('com_einsatzkomponente.einsatzkomponente');
if ($this->params->get('eiko')) : 
?>
<script type="text/javascript">

    Joomla.submitbutton = function(task)
    {
        if (task == 'ausruestung.cancel') {
            Joomla.submitform(task, document.getElementById('ausruestung-form'));
        }
        else {
            
            if (task != 'ausruestung.cancel' && document.formvalidator.isValid(document.getElementById('ausruestung-form'))) {
                
                Joomla.submitform(task, document.getElementById('ausruestung-form'));
            }
            else {
                alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo Route::_('index.php?option=com_einsatzkomponente&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="ausruestung-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'general', Text::_('COM_EINSATZKOMPONENTE_TITLE_AUSRUESTUNG', true)); ?>
        <div class="row">
            <div class="col-md-10 form-horizontal">
                <fieldset class="adminform">

                    				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('image'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('image'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('beschreibung'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('beschreibung'); ?></div>
			</div>
				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo Factory::getApplication()->getIdentity()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

                </fieldset>
            </div>
        </div>
        <?php echo HTMLHelper::_('bootstrap.endTab'); ?>
        

        <?php echo HTMLHelper::_('bootstrap.endTabSet'); ?>

        <div class="control-group">
            <div class="controls">
                <button type="submit" class="validate btn btn-primary"><?php echo Text::_('JSUBMIT'); ?></button>
                <a class="btn" href="<?php echo Route::_('index.php?option=com_einsatzkomponente&task=ausruestungform.cancel'); ?>" title="<?php echo Text::_('JCANCEL'); ?>"><?php echo Text::_('JCANCEL'); ?></a>
            </div>
        </div>
        
        <input type="hidden" name="option" value="com_einsatzkomponente" />
        <input type="hidden" name="task" value="ausruestungform.save" />
        <?php echo HTMLHelper::_('form.token'); ?>

    </div>
</form>
<?php 
else: 
echo 'Keine Eingabe möglich';
endif;
