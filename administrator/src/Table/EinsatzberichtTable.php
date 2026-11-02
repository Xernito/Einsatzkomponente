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

use Joomla\CMS\Table\Asset;
use Joomla\Database\DatabaseInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;
/**
 * einsatzbericht Table class
 */
class EinsatzberichtTable extends Table {
    /**
     * Constructor
     *
     * @param JDatabase A database connector object
     */
    public function __construct(DatabaseInterface $db) {
        parent::__construct('#__eiko_einsatzberichte', 'id', $db);
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
		
		$array['updatedate'] = HTMLHelper::date('now', 'Y-m-d H:i:s');
		

		//Support for multiple or not foreign key field: auswahl_orga
			if(isset($array['auswahl_orga'])) {
				if(is_array($array['auswahl_orga'])){
					$array['auswahl_orga'] = implode(',',$array['auswahl_orga']);
				}
				else if(strrpos($array['auswahl_orga'], ',') != false){
					$array['auswahl_orga'] = explode(',',$array['auswahl_orga']);
				}
			}
			else {
				$array['auswahl_orga'] = ''; }

				//Support for multiple or not foreign key field: vehicles
			if(isset($array['vehicles'])) {
				if(is_array($array['vehicles'])){
					$array['vehicles'] = implode(',',$array['vehicles']);
				}
				else if(strrpos($array['vehicles'], ',') != false){
					$array['vehicles'] = explode(',',$array['vehicles']);
				}
			}
			else {
				$array['vehicles'] = ''; }
			
		//Support for multiple or not foreign key field: ausruestung
			if(isset($array['ausruestung'])){
				if(is_array($array['ausruestung'])){
					$array['ausruestung'] = implode(',',$array['ausruestung']);
				}
				else if(strrpos($array['ausruestung'], ',') != false){
					$array['ausruestung'] = explode(',',$array['ausruestung']);
				}
			}
			else {
				$array['ausruestung'] = ''; }
			
		if(!Factory::getApplication()->getIdentity()->authorise('core.edit.state','com_einsatzkomponente.einsatzbericht.'.$array['id']) && $array['state'] == 1){
			$array['state'] = 0;
		}
		 if(!isset($array['modified_by'])) {
			 $array['modified_by'] = Factory::getApplication()->getIdentity()->id;
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
//        if(!Factory::getApplication()->getIdentity()->authorise('core.admin', 'com_einsatzkomponente.einsatzbericht.'.$array['id'])){
//            $actions = Factory::getACL()->getActions('com_einsatzkomponente','einsatzbericht');
//            $default_actions = Factory::getACL()->getAssetRules('com_einsatzkomponente.einsatzbericht.'.$array['id'])->getData();
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
//    private function JAccessRulestoArray($jaccessrules){ 
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
		
        if ($this->counter > '0'&& $this->id == 0) {
		$this->counter = '0';  }
		
        if (property_exists($this, 'createdate') && $this->id == 0) {
		$this->createdate = HTMLHelper::date('now', 'Y-m-d H:i:s');
		if(!$this->created_by){ 
			$this->created_by = Factory::getApplication()->getIdentity()->id;
		}
        }

        return parent::check();
    }
    
//    /**
//      * Define a namespaced asset name for inclusion in the #__assets table
//      * @return string The asset name 
//      *
//      * @see Table::_getAssetName 
//    */
//    protected function _getAssetName() {
//        $k = $this->_tbl_key;
//        return 'com_einsatzkomponente.einsatzbericht.' . (int) $this->$k;
//    }
// 
//    /**
//      * Returns the parrent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
//      *
//      * @see Table::_getAssetParentId 
//    */
//	protected function _getAssetParentId(Table $table = NULL, $id = NULL) {
//    //protected function _getAssetParentId($table = null, $id = null){
//        // We will retrieve the parent-asset from the Asset-table
//        $assetParent = new Asset($this->getDbo());
//        // Default: if no asset-parent can be found we take the global asset
//        $assetParentId = $assetParent->getRootId();
//        // The item has the component as asset-parent
//        $assetParent->loadByName('com_einsatzkomponente');
//        // Return the found asset-parent-id
//        if ($assetParent->id){
//            $assetParentId=$assetParent->id;
//        }
//        return $assetParentId;
//    }
    
    
}
