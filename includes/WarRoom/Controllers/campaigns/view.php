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

        $sound = $this->request->get('sound') !== null;
        $display = $this->request->get('display') !== null;

        if ($display) {
            $sound = true;
        }

        echo \WarRoom::$twig->render('campaigns/view.html.twig', ['sound' => $sound, 'display' => $display, 'campaign' => $campaign, 'my_links' => $my_links, 'users' => Models\User::get_leaders($campaign)]);
    }

    public function get_liveleaders($id)
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

        $users = Models\User::get_leaders($campaign);
        $leaderboard = array_map(function($user) use($campaign) {
                return [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'clicks' => $user->get_clicks($campaign)
                ];
        }, iterator_to_array($users));

        $links = array_map(function($link) {
                return [
                    'id' => $link->id,
                    'clicks' => $link->clicks,
                    'source_info' => $link->source_info
                ];
        }, iterator_to_array($my_links));

        return [
            'leaderboard' => $leaderboard,
            'links' => $links
        ];
    }

    public function post_link($id)
    {
        try {
            $campaign = Models\Campaign::one($id);
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(401);
        }

        if (!Models\User::me()->is_member($campaign)) {
            throw new \CuteControllers\HttpError(401);
        }

        new Models\Link([
            'userID' => Models\User::me()->id,
            'campaignID' => $id,
            'source_info' => $this->request->post('source_info')
        ]);

        $this->redirect('/campaigns/view/'.$id);
    }

    public function post_join($id)
    {
        try {
            $campaign = Models\Campaign::one($id);
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(401);
        }

        Models\User::me()->join($campaign);
        $this->redirect('/campaigns/view/'.$id);
    }
} 