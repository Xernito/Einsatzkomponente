<?php

/**
 * @package     com_einsatzkomponente
 * @copyright   Copyright (C) 2014. Alle Rechte vorbehalten.
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

namespace Einsatzkomponente\Component\Einsatzkomponente\Administrator\Helper;

defined('_JEXEC') or die;

/**
 * Simple holder of the permissions of the current user (keys contain dots,
 * therefore Registry cannot be used).
 */
class Actions
{
    /**
     * @var bool[]
     */
    private $actions = [];

    public function set(string $action, bool $allowed): void
    {
        $this->actions[$action] = $allowed;
    }

    public function get(string $action, bool $default = false): bool
    {
        return $this->actions[$action] ?? $default;
    }
}
