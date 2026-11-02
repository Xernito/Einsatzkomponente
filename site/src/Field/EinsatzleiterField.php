<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Einsatzkomponente\Component\Einsatzkomponente\Site\Field;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Database\DatabaseInterface;

/**
 * Supports an HTML select list of categories
 */
class EinsatzleiterField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'einsatzleiter';
	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = array();
		$address = array();
        
$id = Factory::getApplication()->getInput()->getVar('id', 0);

$params = ComponentHelper::getParams('com_einsatzkomponente');

$db = Factory::getContainer()->get(DatabaseInterface::class);
$query = 'SELECT id, boss as title FROM #__eiko_einsatzberichte WHERE state="1" GROUP BY boss ORDER BY boss';
$db->setQuery($query);
$arrayDb = $db->loadObjectList();

$html[]='<input class="control-label" type="text"  name="'. $this->name.'"  id="'.$this->id.'"  value="'.$this->value.'" size="'.$this->size.'" />';

if (count($arrayDb)):
$array[] = HTMLHelper::_('select.option', '', Text::_('COM_EINSATZKOMPONENTE_EINSATZLEITER_AUSWAEHLEN'), 'title', 'title');
$array = array_merge($array, $arrayDb);
$html[].= '<br/><br/>'.HTMLHelper::_('select.genericlist', $array, "boss", 'onchange="changeText_einsatzleiter()" ', 'title', 'title', '0');

$html[].='<script type="text/javascript">
function changeText_einsatzleiter(){
    var userInput1 = document.getElementById("boss").value;
	document.getElementById("'.$this->id.'").value = userInput1;
}
</script>';
endif;
        
		return implode($html);
	}
}