<?php

/**
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2014. Alle Rechte vorbehalten.
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

defined('_JEXEC') or die;

use Einsatzkomponente\Component\Einsatzkomponente\Administrator\Extension\EinsatzkomponenteComponent;
use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\HTML\Registry;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

/*
 * Joomla boots a component always from administrator/components/<component>/services/provider.php,
 * for the backend as well as for the frontend. Therefore only this provider exists.
 */
return new class () implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->registerServiceProvider(new MVCFactory('\\Einsatzkomponente\\Component\\Einsatzkomponente'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\Einsatzkomponente\\Component\\Einsatzkomponente'));
        $container->registerServiceProvider(new RouterFactory('\\Einsatzkomponente\\Component\\Einsatzkomponente'));

        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new EinsatzkomponenteComponent($container->get(ComponentDispatcherFactoryInterface::class));

                $component->setRegistry($container->get(Registry::class));
                $component->setMVCFactory($container->get(MVCFactoryInterface::class));
                $component->setRouterFactory($container->get(RouterFactoryInterface::class));

                return $component;
            }
        );
    }
};
