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

//Load admin language file
$lang = Factory::getApplication()->getLanguage();
$lang->load('com_einsatzkomponente', JPATH_ADMINISTRATOR);


$user = Factory::getApplication()->getIdentity();
$userId = $user->get('id');
$listOrder = (string) $this->state->get('list.ordering');
$listDirn = (string) $this->state->get('list.direction');
$canCreate = $user->authorise('core.create', 'com_einsatzkomponente');
$canEdit = $user->authorise('core.edit', 'com_einsatzkomponente');
$canCheckin = $user->authorise('core.manage', 'com_einsatzkomponente');
$canChange = $user->authorise('core.edit.state', 'com_einsatzkomponente');
$canDelete = $user->authorise('core.delete', 'com_einsatzkomponente');
?>

<!--Page Heading-->
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<div class="page-header eiko_header_main">
<h1 class="eiko_header_main_h1"> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1> 
</div>
<br/>
<?php endif;?>

<?php
require JPATH_SITE.'/components/com_einsatzkomponente/tmpl/einsatzarchiv/'.$this->params->get('main_layout','main_layout_1.php').''; 

?>
