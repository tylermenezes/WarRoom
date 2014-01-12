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

        $my_links = Models\Link::find()
                        ->where('userID = ?', Models\User::me()->id)
                        ->where('campaignID = ?', $id)
                        ->all();

        $autorefresh = $this->request->get('autorefresh') !== null;

        echo \WarRoom::$twig->render('campaigns/view.html.twig', ['autorefresh' => $autorefresh, 'campaign' => $campaign, 'my_links' => $my_links, 'users' => Models\User::get_leaders($campaign)]);
    }

    public function post_link($id)
    {
        try {
            $campaign = Models\Campaign::one($id);
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(401);
        }

        new Models\Link([
            'userID' => Models\User::me()->id,
            'campaignID' => $id,
            'source_info' => $this->request->post('source_info')
        ]);

        $this->redirect('/campaigns/view/'.$id);
    }
} 