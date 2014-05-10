<?php
namespace WarRoom\Controllers\campaigns;

use \WarRoom\Models;
use \WarRoom\Traits;

/**
 * 
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) 2014 Tyler Menezes. Released under the Perl Artistic License 2.0.
 *
 * @package     WarRoom\Controllers
 */
class newgroup
{
    use \CuteControllers\Controller;
    use Traits\RequiresLogin;
    use Traits\RequiresAdmin;

    public function get_index()
    {
        echo \WarRoom::$twig->render('campaigns/new_group.html.twig');
    }

    public function post_index()
    {
        $group = new Models\Group([
            'title' => $this->request->post('title'),
            'starts_at' => strtotime($this->request->post('starts_at')),
            'ends_at' => strtotime($this->request->post('ends_at'))
        ]);

        $this->redirect('/campaigns');
    }
} 