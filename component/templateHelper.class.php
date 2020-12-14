<?php

/**
 *      ****  *  *     *  ****  ****  *    *
 *      *  *  *  * *   *  *  *  *  *   *  *
 *      ****  *  *  *  *  *  *  *  *    *
 *      *     *  *   * *  *  *  *  *   *  *
 *      *     *  *    **  ****  ****  *    *
 * @author   Pinoox
 * @link https://www.pinoox.com/
 * @license  https://opensource.org/licenses/MIT MIT License
 */

namespace pinoox\app\com_pinoox_paper\component;

class TemplateHelper
{

    private static $head = [];

    public static function printHead()
    {
        foreach (self::$head as $k=>$h) {
            echo "\t".$h . "\n";
        }
    }

    public static function getHead()
    {
        return self::$head;
    }

    public static function setHead($key, $value)
    {
        self::$head[$key] = $value;
    }

    public static function setMeta($name, $content)
    {
        $meta = '<meta name="' . $name . '" content="' . $content . '"/>';
        self::setHead($name, $meta);
    }

    public static function title($title)
    {
        $site_title = setting('general.site_title');
        $title = !empty($title) ? $site_title . ' - ' . $title : $site_title;
        self::setTitle($title);
        self::setProperty('og:title',$title);
    }

    public static function description($description)
    {
        self::setMeta('description',$description);
        self::setProperty('og:description',$description);
    }

    public static function setTitle($title)
    {
        self::setHead('title','<title>' . $title . '</title>');
    }

    public static function setProperty($name, $content)
    {
        $meta = '<meta property="' . $name . '" content="' . $content . '"/>';
        self::setHead($name, $meta);
    }

    public static function unsetHead($key)
    {
        if (isset(self::$head[$key]))
            unset(self::$head[$key]);
    }

    public static function setLink($rel,$href)
    {
        $meta = '<link rel="'.$rel.'" href="'.$href.'" />';
        self::setHead($rel, $meta);
    }

    public static function initData()
    {
        $site_title = setting('general.site_title');
        $site_description = setting('general.site_description');

        $image = setting('general.site_logo');
        $image = !empty($image)? furl($image) : $image;

        self::setTitle($site_title);
        self::setMeta('description',$site_description);

        if(!empty($image))
        {
            self::setLink('shortcut icon',$image);
            self::setLink('apple-touch-icon',$image);
            self::setProperty('og:image',$image);
            self::setProperty('og:image:width',200);
            self::setProperty('og:image:height',200);
        }

        self::setProperty('og:site_name',$site_title);
        self::setProperty('og:type','website');
        self::setProperty('og:title',$site_title);
        self::setProperty('og:description',$site_description);

        self::setProperty('og:url',url());
    }
}