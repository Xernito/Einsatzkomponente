<?php
/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Einsatzkomponente\Component\Einsatzkomponente\Administrator\Table;

defined('_JEXEC') or die;

use Joomla\Database\DatabaseInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;
/**
 * kategorie Table class
 */
class KategorieTable extends Table {
    /**
     * Constructor
     *
     * @param JDatabase A database connector object
     */
    public function __construct(DatabaseInterface $db) {
        parent::__construct('#__eiko_tickerkat', 'id', $db);
				// Set the alias since the column is called state
				$this->setColumnAlias('published', 'state');
    }
    /**
     * Overloaded bind function to pre-process the params.
     *
     * @param	array		Named array
     * @return	null|string	null is operation was satisfactory, otherwise returns an error
     * @see		JTable:bind
     * @since	1.5
     */
    public function bind($array, $ignore = '') {
        
		if(!Factory::getApplication()->getIdentity()->authorise('core.edit.state','com_einsatzkomponente.kategorie.'.$array['id']) && $array['state'] == 1){
			$array['state'] = 0;
		}
		if(isset($array['created_by']) || $array['created_by'] == 0){
			$array['created_by'] = Factory::getApplication()->getIdentity()->id;
		}
        if (isset($array['params']) && is_array($array['params'])) {
            $registry = new Registry();
            $registry->loadArray($array['params']);
            $array['params'] = (string) $registry;
        }
        if (isset($array['metadata']) && is_array($array['metadata'])) {
            $registry = new Registry();
            $registry->loadArray($array['metadata']);
            $array['metadata'] = (string) $registry;
        }
//        if(!Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_einsatzkomponente.kategorie.'.$array['id'])){
//           $actions = Factory::getACL()->getActions('com_einsatzkomponente','kategorie');
//          $default_actions = Factory::getACL()->getAssetRules('com_einsatzkomponente.kategorie.'.$array['id'])->getData();
//            $array_jaccess = array();
//            foreach($actions as $action){
//                $array_jaccess[$action->name] = $default_actions[$action->name];
//            }
//            $array['rules'] = $this->JAccessRulestoArray($array_jaccess);
//        }
        //Bind the rules for ACL where supported.
//		if (isset($array['rules']) && is_array($array['rules'])) {
//			$this->setRules($array['rules']);
//		}
        return parent::bind($array, $ignore);
    }
    
    /**
     * This function convert an array of JAccessRule objects into an rules array.
     * @param type $jaccessrules an arrao of JAccessRule objects.
     */
//   private function JAccessRulestoArray($jaccessrules){
//        $rules = array();
//        foreach($jaccessrules as $action => $jaccess){
//            $actions = array();
//            foreach($jaccess->getData() as $group => $allow){
//                $actions[$group] = ((bool)$allow);
//            }
//            $rules[$action] = $actions;
//        }
//        return $rules;
//    }
    /**
     * Overloaded check function
     */
    public function check() {
        //If there is an ordering column and this is a new row then get the next ordering value
        if (property_exists($this, 'ordering') && $this->id == 0) {
            $this->ordering = self::getNextOrder();
        }
        return parent::check();
    }
    
}
