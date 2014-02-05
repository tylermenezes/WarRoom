<?php
namespace WarRoom\Traits;

use \WarRoom\Models;

/**
 * Checks if the logged in user is an admin
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes.
 *
 */
trait RequiresAdmin
{
    public function before_check_admin()
    {
        if (!Models\User::me()->is_admin) {
            throw new \CuteControllers\HttpError(403);
        }
    }
} 