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
        $open_campaigns = Models\Campaign::find()
            ->where('starts_at IS NULL OR starts_at < NOW()')
            ->where('ends_at IS NULL OR ends_at > NOW()')
            ->all();
        echo \WarRoom::$twig->render('campaigns/index.html.twig', ['campaigns' => $open_campaigns]);
    }
} 