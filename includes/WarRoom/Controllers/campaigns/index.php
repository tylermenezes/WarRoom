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
class index
{
    use \CuteControllers\Controller;
    use Traits\RequiresLogin;

    public function get_index()
    {
        $my_campaigns = Models\Campaign::find()
            ->where('campaigns.starts_at IS NULL OR campaigns.starts_at < NOW()')
            ->where('campaigns.ends_at IS NULL OR campaigns.ends_at > NOW()')
            ->join('campaigns_users ON (campaigns.campaignID = campaigns_users.campaignID)')
            ->where('campaigns_users.userID = ?', Models\User::me()->id)
            ->all();

        $other_campaigns = Models\Campaign::find()
            ->where('campaigns.starts_at IS NULL OR campaigns.starts_at < NOW()')
            ->where('campaigns.ends_at IS NULL OR campaigns.ends_at > NOW()')
            ->where('(SELECT COUNT(*) FROM campaigns_users WHERE campaigns_users.campaignID = campaigns.campaignID AND campaigns_users.userID = ?) = 0', Models\User::me()->id)
            ->all();
        echo \WarRoom::$twig->render('campaigns/index.html.twig', ['my_campaigns' => $my_campaigns, 'other_campaigns' => $other_campaigns]);
    }
} 