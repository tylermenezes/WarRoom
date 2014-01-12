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
class Click extends \TinyDb\Orm
{
    public static $table_name = 'clicks';

    public $clickID;

    /*
     * @foreign \WarRoom\Models\Link link
     */
    public $linkID;

    public $user_agent;
    public $ip;

    public $created_at;
} 