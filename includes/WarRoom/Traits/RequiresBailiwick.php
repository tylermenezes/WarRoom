<?php
namespace WarRoom\Traits;

use \WarRoom\Models;

/**
 * Checks if the logged in user's email is in bailiwick with the staff email
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes.
 *
 */
trait RequiresBailiwick
{
    public function before_check_admin()
    {
        if (!Models\User::me()->in_bailiwick) {
            throw new \CuteControllers\HttpError(403);
        }
    }
} 