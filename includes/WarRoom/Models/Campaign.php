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
class Campaign extends \TinyDb\Orm
{
    public static $table_name = 'campaigns';

    public $campaignID;
    public $link;

    public $created_at;
    public $starts_at;
    public $ends_at;

    /**
     * @foreign \WarRoom\Models\Group group
     */
    public $groupID;

    public $title;
    public $description;
    public $fb_meta;
    public $twitter_meta;

    public function sync_og()
    {
        $contents = file_get_contents($this->link);

        $this->title = $this->get_tag($contents, '//*/title');
        $this->description = $this->get_tag($contents, '//*/meta[name="description"]', 'content');

        $this->fb_meta = json_encode($this->get_meta($contents, 'og'));
        $this->twitter_meta = json_encode($this->get_meta($contents, 'twitter'));

        $this->update();
    }

    public function get_tags()
    {
        $general_tags  = '<title>'.htmlentities($this->title).'</title>'."\n";
        $general_tags .= '<meta name="description" content="'.str_replace('"', '', $this->description).'" />'."\n";

        $fb_tags = $this->collapse_meta(json_decode($this->fb_meta, true), 'og', 'property');
        $twitter_tags = $this->collapse_meta(json_decode($this->twitter_meta, true), 'twitter', 'name');

        return "$general_tags\n$fb_tags\n$twitter_tags";
    }

    private function collapse_meta($tags, $prefix, $attr)
    {
        $collapsed = '';
        if ($tags) {
            foreach ($tags as $tag=>$content) {
                $collapsed .= '<meta '.$attr.'="'.$tag.'" content="'.str_replace('"', '', $content).'" />'."\n";
            }
        }

        return $collapsed;
    }

    private function get_meta($contents, $type)
    {
        libxml_use_internal_errors(true);
        $doc = new \DomDocument();
        $doc->loadHTML($contents);
        $xpath = new \DOMXPath($doc);
        $query = '//*/meta[starts-with(@name, \''.$type.':\')]|//*/meta[starts-with(@property, \''.$type.':\')]';
        $metas = $xpath->query($query);
        foreach ($metas as $meta) {
            $property = $meta->getAttribute('property') ? $meta->getAttribute('property') : $meta->getAttribute('name');
            $content = $meta->getAttribute('content');
            $rmetas[$property] = $content;
        }

        if (isset($rmetas[$type.':url'])) {
            unset($rmetas[$type.':url']);
        }

        return $rmetas;
    }

    private function get_tag($contents, $query, $attr = null)
    {
        libxml_use_internal_errors(true);
        $doc = new \DomDocument();
        $doc->loadHTML($contents);
        $xpath = new \DOMXPath($doc);
        $tags = $xpath->query($query);
        foreach ($tags as $tag) {
            if ($attr) {
                return $tag->getAttribute($attr);
            } else {
                return $tag->nodeValue;
            }
        }
    }
} 