<?php
namespace WarRoom\Models\Campaign;

/**
 * Maps a user to a campaign.
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) Tyler Menezes.
 *
 */
class User extends \TinyDb\Orm
{
    public static $table_name = 'campaigns_users';

    public $campaigns_userID;
    public $campaignID;
    public $userID;
    public $created_at;
} 