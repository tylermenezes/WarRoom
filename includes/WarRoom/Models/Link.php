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
class Link extends \TinyDb\Orm
{
    public static $table_name = 'links';

    public $linkID;

    /*
     * @foreign \WarRoom\Models\Campaign campaign
     */
    public $campaignID;

    /*
     * @foreign \WarRoom\Models\User user
     */
    public $userID;
    public $created_at;
    public $source_info;
} 