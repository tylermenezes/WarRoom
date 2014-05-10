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

    public function get_index($id)
    {
        try {
            $group = Models\Group::one($id);
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(401);
        }

        echo \WarRoom::$twig->render('campaigns/message_group.html.twig', ['group' => $group]);
    }

    public function post_index($id)
    {
        try {
            $group = Models\Group::one($id);
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(401);
        }

        $group->message = $this->request->post('message');

        $this->redirect('/campaigns/message_group/'.$id);
    }
} 