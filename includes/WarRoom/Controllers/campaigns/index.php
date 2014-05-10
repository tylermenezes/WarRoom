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
        $current_groups = Models\Group::find()
            ->where('groups.starts_at < NOW()')
            ->where('groups.ends_at > NOW()')
            ->all();

        $archived_groups = Models\Group::find()
            ->where('groups.ends_at < NOW()')
            ->all();

        $upcoming_groups = Models\Group::find()
            ->where('groups.starts_at > NOW()')
            ->all();

        echo \WarRoom::$twig->render('campaigns/index.html.twig', [
            'current_groups' => $current_groups,
            'archived_groups' => $archived_groups,
            'upcoming_groups' => $upcoming_groups
        ]);
    }
} 