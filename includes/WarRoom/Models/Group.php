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
class Group extends \TinyDb\Orm
{
    public static $table_name = 'groups';

    public $groupID;
    public $title;
    public $message;

    public $created_at;
    public $starts_at;
    public $ends_at;

    public function get_campaigns()
    {
        return Campaign::find()
                       ->where('groupID = ?', $this->groupID)
                       ->all();
    }
} 