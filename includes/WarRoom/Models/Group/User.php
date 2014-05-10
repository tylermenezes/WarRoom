<?php
namespace WarRoom\Models\Group;

/**
 * Maps a user to a campaign.
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes.
 *
 */
class User extends \TinyDb\Orm
{
    public static $table_name = 'groups_users';

    public $groups_userID;
    public $groupID;
    public $userID;
    public $created_at;
} 