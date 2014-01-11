<?php
namespace WarRoom\Models;

/**
 * Represents a user.
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) 2014 Tyler Menezes. Released under the Perl Artistic License 2.0.
 *
 * @package     WarRoom\Models
 */
class User extends \TinyDb\Orm
{
    use \Jetpack\Traits\User;

    public static $table_name = 'users';

    public $userID;
    public $email;

    public $first_name;
    public $last_name;

    public function get_clicks($campaign)
    {
        return count(Click::find()
                        ->join('links ON (links.linkID = clicks.linkID)')
                        ->where('links.userID = ?', $this->id)
                        ->where('links.campaignID = ?', $campaign->id)
                        ->all());
    }

    public static function get_leaders($campaign)
    {
        return self::find()
                    ->select('users.*, (SELECT COUNT(*) FROM clicks LEFT JOIN links ON (links.linkID = clicks.linkID) WHERE (links.campaignID = '.$campaign->id.' AND links.userID = users.userID)) as clicks')
                    ->order_by('clicks DESC')
                    ->all();
    }
} 