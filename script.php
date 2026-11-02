<?php

/**
 * @package     com_einsatzkomponente
 *
 * @author      Ralf Meyer <ralf.meyer@mail.de>
 * @copyright   Copyright (C) 2014. Alle Rechte vorbehalten.
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Application\AdministratorApplication;
use Joomla\CMS\Installer\Adapter\ComponentAdapter;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Language\Text;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

return new class () implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->set(
            InstallerScriptInterface::class,
            new class ($container->get(AdministratorApplication::class)) implements InstallerScriptInterface {
                /**
                 * Minimum Joomla version to install the component on.
                 */
                private string $minimumJoomla = '6.0.0';

                /**
                 * Minimum PHP version to install the component on.
                 */
                private string $minimumPhp = '8.3.0';

                public function __construct(private AdministratorApplication $app)
                {
                }

                public function install(InstallerAdapter $parent): bool
                {
                    return true;
                }

                public function update(InstallerAdapter $parent): bool
                {
                    return true;
                }

                public function uninstall(InstallerAdapter $parent): bool
                {
                    $this->app->enqueueMessage('Die Datenbanktabellen (#__eiko_*) müssen Sie manuell löschen ...', 'notice');

                    return true;
                }

                public function preflight(string $type, InstallerAdapter $parent): bool
                {
                    if ($type === 'uninstall') {
                        return true;
                    }

                    if (version_compare(PHP_VERSION, $this->minimumPhp, '<')) {
                        $this->app->enqueueMessage(
                            sprintf('Einsatzkomponente benötigt mindestens PHP %s (aktuell: %s).', $this->minimumPhp, PHP_VERSION),
                            'error'
                        );

                        return false;
                    }

                    if (version_compare(JVERSION, $this->minimumJoomla, '<')) {
                        $this->app->enqueueMessage(
                            sprintf('Einsatzkomponente benötigt mindestens Joomla %s (aktuell: %s).', $this->minimumJoomla, JVERSION),
                            'error'
                        );

                        return false;
                    }

                    return true;
                }

                public function postflight(string $type, InstallerAdapter $parent): bool
                {
                    if ($type === 'install' && $parent instanceof ComponentAdapter) {
                        $parent->getParent()->setRedirectUrl('index.php?option=com_einsatzkomponente&view=installation');
                    } elseif ($type === 'update' && $parent instanceof ComponentAdapter) {
                        $parent->getParent()->setRedirectUrl('index.php?option=com_einsatzkomponente&view=installation');
                    }

                    return true;
                }
            }
        );
    }
};
