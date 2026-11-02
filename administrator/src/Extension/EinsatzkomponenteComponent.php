<?php

/**
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2014. Alle Rechte vorbehalten.
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

namespace Einsatzkomponente\Component\Einsatzkomponente\Administrator\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Component\Router\RouterServiceInterface;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;

/**
 * Component class for com_einsatzkomponente.
 */
class EinsatzkomponenteComponent extends MVCComponent implements RouterServiceInterface
{
    use RouterServiceTrait;
    use HTMLRegistryAwareTrait;
}
