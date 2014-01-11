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
class newc
{
    use \CuteControllers\Controller;
    use Traits\RequiresLogin;

    public function get_index()
    {
        echo \WarRoom::$twig->render('campaigns/new.html.twig');
    }

    public function post_index()
    {
        $campaign = new Models\Campaign([
            'link' => $this->request->post('link')
        ]);
        $campaign->sync_og();

        $this->redirect('/campaigns/view/'.$campaign->id);
    }
} 