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
    public $is_admin;

    public $first_name;
    public $last_name;

    public function get_in_bailiwick()
    {
        $domain = array_pop(explode('@', $this->email));
        return $domain === \WarRoom::$config->email_domain;
    }

    public function get_clicks($campaign)
    {
        return count(Click::find()
                        ->join('links ON (links.linkID = clicks.linkID)')
                        ->where('links.userID = ?', $this->id)
                        ->where('links.campaignID = ?', $campaign->id)
                        ->all());
    }

    public function join($campaign)
    {
        if (!$this->is_member($campaign)) {
            new \WarRoom\Models\Campaign\User([
                'userID' => $this->id,
                'campaignID' => $campaign->id
            ]);
        }
    }

    public function is_member($campaign)
    {
        return count(\WarRoom\Models\Campaign\User::find()
                    ->where('userID = ?', $this->userID)
                    ->where('campaignID = ?', $campaign->id)
                    ->all()) > 0;
    }

    public static function get_leaders($campaign)
    {
        return self::find()
                    ->select('users.*, (SELECT COUNT(*) FROM clicks LEFT JOIN links ON (links.linkID = clicks.linkID) WHERE (links.campaignID = '.$campaign->id.' AND links.userID = users.userID)) as clicks')
                    ->join('campaigns_users ON (campaigns_users.userID = users.userID)')
                    ->where('campaigns_users.campaignID = ?', $campaign->id)
                    ->order_by('clicks DESC')
                    ->all();
    }
} 