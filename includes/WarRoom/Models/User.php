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

    public function get_clicks($group)
    {
        return count(Click::find()
                        ->join('links ON (links.linkID = clicks.linkID)')
                        ->where('links.userID = ?', $this->id)
                        ->where('links.groupID = ?', $group->id)
                        ->all());
    }

    public function join($group)
    {
        if (!$this->is_member($group)) {
            new \WarRoom\Models\Group\User([
                'userID' => $this->id,
                'groupID' => $group->id
            ]);
        }
    }

    public function is_member($group)
    {
        return count(\WarRoom\Models\Group\User::find()
                    ->where('userID = ?', $this->userID)
                    ->where('groupID = ?', $group->id)
                    ->all()) > 0;
    }

    public static function get_leaders($group)
    {
        return self::find()
                    ->select('users.*, (SELECT COUNT(*) FROM clicks LEFT JOIN links ON (links.linkID = clicks.linkID) WHERE (links.groupID = '.$group->id.' AND links.userID = users.userID)) as clicks')
                    ->join('groups_users ON (groups_users.userID = users.userID)')
                    ->where('groups_users.groupID = ?', $group->id)
                    ->order_by('clicks DESC')
                    ->all();
    }
} 