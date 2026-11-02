<?php

/**
 * @version     3.15.0
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2017 by Ralf Meyer. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ralf Meyer <ralf.meyer@mail.de> - https://einsatzkomponente.de
 */

namespace Einsatzkomponente\Component\Einsatzkomponente\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
/**
 * Einsatzfahrzeug controller class.
 *
 * @since  1.6
 */
class EinsatzfahrzeugController extends BaseController
{
	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	public function edit()
	{
		$app = Factory::getApplication();

		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $app->getUserState('com_einsatzkomponente.edit.einsatzfahrzeug.id');
		$editId     = $app->getInput()->getInt('id', 0);

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_einsatzkomponente.edit.einsatzfahrzeug.id', $editId);

		// Get the model.
		$model = $this->getModel('Einsatzfahrzeug');

		// Check out the item
		if ($editId)
		{
			$model->checkout($editId);
		}

		// Check in the previous user.
		if ($previousId && $previousId !== $editId)
		{
			$model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(Route::_('index.php?option=com_einsatzkomponente&view=einsatzfahrzeugform&layout=edit', false));
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return    void
	 *
	 * @throws Exception
	 * @since    1.6
	 */
	public function publish()
	{
		// Initialise variables.
		$app = Factory::getApplication();

		// Checking if the user can remove object
		$user = Factory::getApplication()->getIdentity();

		if ($user->authorise('core.edit', 'com_einsatzkomponente') || $user->authorise('core.edit.state', 'com_einsatzkomponente'))
		{
			$model = $this->getModel('Einsatzfahrzeug');

			// Get the user data.
			$id    = $app->getInput()->getInt('id');
			$state = $app->getInput()->getInt('state');

			// Attempt to save the data.
			$return = $model->publish($id, $state);

			// Check for errors.
			if ($return === false)
			{
				$this->setMessage(Text::sprintf('Save failed: %s', $model->getError()), 'warning');
			}

			// Clear the profile id from the session.
			$app->setUserState('com_einsatzkomponente.edit.einsatzfahrzeug.id', null);

			// Flush the data from the session.
			$app->setUserState('com_einsatzkomponente.edit.einsatzfahrzeug.data', null);

			// Redirect to the list screen.
			$this->setMessage(Text::_('COM_EINSATZKOMPONENTE_ITEM_SAVED_SUCCESSFULLY'));
			$menu = Factory::getApplication()->getMenu();
			$item = $menu->getActive();

			if (!$item)
			{
				// If there isn't any menu item active, redirect to list view
				$this->setRedirect(Route::_('index.php?option=com_einsatzkomponente&view=einsatzfahrzeuge', false));
			}
			else
			{
				$this->setRedirect(Route::_($item->link . $menuitemid, false));
			}
		}
		else
		{
			throw new \Exception(Text::_('JERROR_ALERTNOAUTHOR'), 403);
		}
	}

	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function remove()
	{
		// Initialise variables.
		$app = Factory::getApplication();

		// Checking if the user can remove object
		$user = Factory::getApplication()->getIdentity();

		if ($user->authorise('core.delete', 'com_einsatzkomponente'))
		{
			$model = $this->getModel('Einsatzfahrzeug');

			// Get the user data.
			$id = $app->getInput()->getInt('id', 0);

			// Attempt to save the data.
			$return = $model->delete($id);

			// Check for errors.
			if ($return === false)
			{
				$this->setMessage(Text::sprintf('Delete failed', $model->getError()), 'warning');
			}
			else
			{
				// Check in the profile.
				if ($return)
				{
					$model->checkin($return);
				}

				// Clear the profile id from the session.
				$app->setUserState('com_einsatzkomponente.edit.einsatzfahrzeug.id', null);

				// Flush the data from the session.
				$app->setUserState('com_einsatzkomponente.edit.einsatzfahrzeug.data', null);

				$this->setMessage(Text::_('COM_EINSATZKOMPONENTE_ITEM_DELETED_SUCCESSFULLY'));
			}

			// Redirect to the list screen.
			$menu = Factory::getApplication()->getMenu();
			$item = $menu->getActive();
			$this->setRedirect(Route::_($item->link, false));
		}
		else
		{
			throw new \Exception(Text::_('JERROR_ALERTNOAUTHOR'), 403);
		}
	}
}
