<?php

/**
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2014. Alle Rechte vorbehalten.
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

namespace Einsatzkomponente\Component\Einsatzkomponente\Site\Service;

defined('_JEXEC') or die;

use Joomla\CMS\Component\Router\RouterBase;

/**
 * Router of com_einsatzkomponente. Keeps the URL scheme of the Joomla 3 version
 * (/view/id and /view/task).
 */
class Router extends RouterBase
{
    public function build(&$query)
    {
        $segments = [];

        if (isset($query['task'])) {
            $segments[] = implode('/', explode('.', $query['task']));
            unset($query['task']);
        }

        if (isset($query['view'])) {
            $segments[] = $query['view'];
            unset($query['view']);
        }

        if (isset($query['id'])) {
            $segments[] = $query['id'];
            unset($query['id']);
        }

        return $segments;
    }

    public function parse(&$segments)
    {
        $vars = [];

        // The view is always the first element
        $vars['view'] = array_shift($segments);

        while (!empty($segments)) {
            $segment = array_pop($segments);

            if (is_numeric($segment)) {
                $vars['id'] = $segment;
            } else {
                $vars['task'] = $vars['view'] . '.' . $segment;
            }
        }

        return $vars;
    }
}
