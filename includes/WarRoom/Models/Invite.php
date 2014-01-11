<?php
namespace WarRoom\Models;

/**
 * 
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) 2014 Tyler Menezes. Released under the Perl Artistic License 2.0.
 *
 * @package     WarRoom\Models
 */
class Invite extends \TinyDb\Orm
{
    public static $table_name = 'invites';

    public $inviteID;
    public $email;
    public $created_at;
    public $used_at;

    public static function mint($for_email)
    {
        $id = hash('sha256', implode('$', [time(), mt_rand(0, mt_getrandmax())]));

        new static([
            'inviteID' => $id,
            'email' => $for_email,
        ]);

        return $id;
    }
}