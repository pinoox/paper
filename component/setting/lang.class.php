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

namespace pinoox\app\com_pinoox_paper\component\setting;

use pinoox\component\HelperString;
use pinoox\component\Lang as LangPinCore;

class Lang
{
    private static $obj = null;
    private $data = [];
    private $path = null;

    public function __construct($path)
    {
        $this->setPath($path);
    }

    private function setPath($path)
    {
        if (!HelperString::lastHas($path, DIRECTORY_SEPARATOR))
            $path .= DIRECTORY_SEPARATOR;

        $this->path = $path . 'lang' . DIRECTORY_SEPARATOR . LangPinCore::current() . DIRECTORY_SEPARATOR;
    }

    public static function init($path)
    {
        self::$obj = new Lang($path);

        return self::$obj;
    }

    public function get($value)
    {
        if (empty($value)) return null;
        $default = '{' . $value . '}';
        $info = explode('.', $value);
        $filename = array_shift($info);
        $filename = str_replace(['/', '\\'], '>', $filename);

        if (!isset($this->data[$filename])) {
            $this->data[$filename] = $this->loadFile($filename);
        }

        $result = $this->result($filename, $info);
        return (!empty($result)) ? $result : $default;
    }

    private function loadFile($filename)
    {

        $file = $this->path . $filename . '.lang.php';

        if (is_file($file)) {
            return (include $file);
        }

        return null;
    }

    private function result($filename, $info)
    {
        $result = $this->data[$filename];
        foreach ($info as $value) {
            if (isset($result[$value])) {
                $result = $result[$value];
            } else {
                $result = null;
                break;
            }
        }

        return $result;
    }
}