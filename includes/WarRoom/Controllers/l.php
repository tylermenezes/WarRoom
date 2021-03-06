<?php
namespace WarRoom\Controllers;

use \WarRoom\Models;

/**
 * Draws links
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) 2014 Tyler Menezes. Released under the Perl Artistic License 2.0.
 *
 * @package     WarRoom\Controllers
 */
class l
{
    use \CuteControllers\Controller;

    public function get_index($linkID)
    {
        try {
            $link = Models\Link::one($linkID);
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(401);
        }

        $campaign = Models\Campaign::one($link->campaignID);
        $user = Models\User::one($link->userID);
        $redir_link = $campaign->link.'?utm_source=warroom&utm_medium='.$user->first_name.$user->last_name.'&utm_term='.$link->source_info;

        echo \WarRoom::$twig->render('l.html.twig', ['link' => $link, 'campaign' => $campaign, 'redir_link' => $redir_link]);
    }

    public function get_count($linkID)
    {
        header("Content-type: image/png");
        if (!Models\User::is_logged_in()) {
            new Models\Click([
                'linkID' => $linkID,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'ip' => $this->request->user_ip
            ]);
        }
    }
}