<?php
namespace WarRoom\Traits;

use \WarRoom\Models;

/**
 * 
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) 2014 Tyler Menezes. Released under the Perl Artistic License 2.0.
 *
 * @package     WarRoom\Traits
 */
trait RequiresLogin
{
    public function before_check_login()
    {
        if (!Models\User::is_logged_in()) {
            throw new \CuteControllers\HttpError(401);
        }
    }
} 