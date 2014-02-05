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
 * @package     WarRoom\Controllers\campaigns
 */
class view
{
    use \CuteControllers\Controller;
    use Traits\RequiresLogin;

    public function get_index($id)
    {
        try {
            $campaign = Models\Campaign::one($id);
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(401);
        }

        echo \WarRoom::$twig->render('campaigns/invite.html.twig', ['campaign' => $campaign]);
    }
} 