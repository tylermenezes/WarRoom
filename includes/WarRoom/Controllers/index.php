<?php
namespace WarRoom\Controllers;

use \WarRoom\Models;

/**
 * Redirects people
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) 2014 Tyler Menezes. Released under the Perl Artistic License 2.0.
 *
 * @package     WarRoom\Controllers
 */
class index
{
    use \CuteControllers\Controller;

    public function get_index()
    {
        if (Models\User::is_logged_in()) {
            $this->redirect('/campaigns');
        } else {
            $this->redirect('/login');
        }
    }
}