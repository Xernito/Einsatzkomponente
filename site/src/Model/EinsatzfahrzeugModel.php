<?php

/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Einsatzkomponente\Component\Einsatzkomponente\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Table\Table;
use Joomla\CMS\User\UserFactoryInterface;
use Joomla\Database\DatabaseInterface;
use Joomla\Utilities\ArrayHelper;
/**
 * Einsatzkomponente model.
 *
 * @since  1.6
 */
class EinsatzfahrzeugModel extends ItemModel
{

    protected $_item = null;
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return void
	 *
	 * @since    1.6
	 *
	 */
	protected function populateState()
	{
		$app = Factory::getApplication();

		// Load state from the request userState on edit or from the passed variable on default
		if (Factory::getApplication()->getInput()->get('layout') == 'edit')
		{
			$id = Factory::getApplication()->getUserState('com_einsatzkomponente.edit.einsatzfahrzeug.id');
		}
		else
		{
			$id = Factory::getApplication()->getInput()->get('id');
			Factory::getApplication()->setUserState('com_einsatzkomponente.edit.einsatzfahrzeug.id', $id);
		}

		$this->setState('einsatzfahrzeug.id', $id);

		// Load the parameters.
		$params       = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
			$this->setState('einsatzfahrzeug.id', (int)$params_array['item_id']);
		}

		$this->setState('params', $params);
	}

		/**
	 * Returns the item (ItemModelInterface); the data is loaded by getData().
	 *
	 * @param   integer|null  $pk  The id of the item.
	 *
	 * @return  object|false
	 */
	public function getItem($pk = null)
	{
		return $this->getData($pk);
	}

/**
	 * Method to get an object.
	 *
	 * @param   integer  $id  The id of the object to get.
	 *
	 * @return  mixed    Object on success, false on failure.
	 */
	public function getData($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id))
			{
				$id = $this->getState('einsatzfahrzeug.id');
			}

			// Get a level row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			if ($table->load($id))
			{
				// Check published state.
				if ($published = $this->getState('filter.published'))
				{
					if ($table->state != $published)
					{
						return $this->_item;
					}
				}

				// Convert the JTable to a clean JObject.
				$properties  = $table->getProperties(1);
				$this->_item = ArrayHelper::toObject($properties, 'stdClass');
			}
		}

			if ($this->_item->state == '2'): $this->_item->name = $this->_item->name.' (a.D.)';endif;

			if (isset($this->_item->department) && $this->_item->department != '') {
				if(is_object($this->_item->department)){
					$this->_item->department = ArrayHelper::fromObject($this->_item->department);
				}
				$values = (is_array($this->_item->department)) ? $this->_item->department : explode(',', (string) $this->_item->department);

				$textValue = array();
				foreach ($values as $value){
					$db = Factory::getContainer()->get(DatabaseInterface::class);
					$query = $db->getQuery(true);
					$query
							->select('name')
							->from('#__eiko_organisationen')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->name;
					}
				}

			$this->_item->department = !empty($textValue) ? implode(', ', $textValue) : $this->_item->department;

			}

			if (isset($this->_item->ausruestung) && $this->_item->ausruestung != '') {
				if(is_object($this->_item->ausruestung)){
					$this->_item->ausruestung = ArrayHelper::fromObject($this->_item->ausruestung);
				}
				$values = (is_array($this->_item->ausruestung)) ? $this->_item->ausruestung : explode(',', (string) $this->_item->ausruestung);

				$textValue = array();
				foreach ($values as $value){
					$db = Factory::getContainer()->get(DatabaseInterface::class);
					$query = $db->getQuery(true);
					$query
							->select('name')
							->from('#__eiko_ausruestung')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->name;
					}
				}

			$this->_item->ausruestung = !empty($textValue) ? implode(', ', $textValue) : $this->_item->ausruestung;

			}if (isset($this->_item->created_by) )
		{
			$this->_item->created_by_name = Factory::getContainer()->get(UserFactoryInterface::class)->loadUserById($this->_item->created_by)->name;
		}

		return $this->_item;
	}

	/**
	 * Get an instance of JTable class
	 *
	 * @param   string  $type    Name of the JTable class to get an instance of.
	 * @param   string  $prefix  Prefix for the table class name. Optional.
	 * @param   array   $config  Array of configuration values for the JTable object. Optional.
	 *
	 * @return  JTable|bool JTable if success, false on failure.
	 */
	public function getTable($type = 'Einsatzfahrzeug', $prefix = '', $config = [])
    {
        return new \Einsatzkomponente\Component\Einsatzkomponente\Administrator\Table\EinsatzfahrzeugTable(Factory::getContainer()->get(DatabaseInterface::class));
    }

	/**
	 * Get the id of an item by alias
	 *
	 * @param   string  $alias  Item alias
	 *
	 * @return  mixed
	 */
	public function getItemIdByAlias($alias)
	{
		$table = $this->getTable();

		$table->load(array('alias' => $alias));

		return $table->id;
	}

	/**
	 * Method to check in an item.
	 *
	 * @param   integer  $id  The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function checkin($id = null)
	{
		// Get the id.
		$id = (!empty($id)) ? $id : (int) $this->getState('einsatzfahrzeug.id');

		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Attempt to check the row in.
			if (method_exists($table, 'checkin'))
			{
				if (!$table->checkin($id))
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Method to check out an item for editing.
	 *
	 * @param   integer  $id  The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function checkout($id = null)
	{
		// Get the user id.
		$id = (!empty($id)) ? $id : (int) $this->getState('einsatzfahrzeug.id');

		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Get the current user object.
			$user = Factory::getApplication()->getIdentity();

			// Attempt to check the row out.
			if (method_exists($table, 'checkout'))
			{
				if (!$table->checkout($user->get('id'), $id))
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Get the name of a category by id
	 *
	 * @param   int  $id  Category id
	 *
	 * @return  Object|null	Object if success, null in case of failure
	 */
	public function getCategoryName($id)
	{
		$db    = Factory::getContainer()->get(DatabaseInterface::class);
		$query = $db->getQuery(true);
		$query
			->select('title')
			->from('#__categories')
			->where('id = ' . $id);
		$db->setQuery($query);

		return $db->loadObject();
	}

	/**
	 * Publish the element
	 *
	 * @param   int  $id     Item id
	 * @param   int  $state  Publish state
	 *
	 * @return  boolean
	 */
	public function publish($id, $state)
	{
		$table = $this->getTable();
		$table->load($id);
		$table->state = $state;

		return $table->store();
	}

	/**
	 * Method to delete an item
	 *
	 * @param   int  $id  Element id
	 *
	 * @return  bool
	 */
	public function delete($id)
	{
		$table = $this->getTable();

		return $table->delete($id);
	}

	
}
