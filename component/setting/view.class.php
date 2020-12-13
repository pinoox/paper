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

use pinoox\component\File;
use pinoox\component\HelperString;

class View
{
    private static $obj = null;
    private $data = [];
    private $path = null;

    /**
     * @var Lang|null
     */
    private $lang = null;

    public function __construct($path)
    {
        $this->setPath($path);
        $this->lang = Lang::init($path);
    }

    private function setPath($path)
    {
        if (!HelperString::lastHas($path, DIRECTORY_SEPARATOR))
            $path .= DIRECTORY_SEPARATOR;

        $this->path = $path . 'view' . DIRECTORY_SEPARATOR;
    }

    public static function init($path)
    {
        self::$obj = new View($path);

        return self::$obj;
    }

    public function getValues($filename)
    {
        $result = [];
        $settings = self::get($filename);
        if (!empty($settings)) {
            $settings = $settings['settings'];
            foreach ($settings as $setting) {
                $key = $setting['key'];
                $result[$key] = $this->getValueSetting($setting);
            }
        }
        return $result;
    }

    private function getValueSetting($setting)
    {
        $value = isset($setting['value']) ? $setting['value'] : null;
        if($setting['type'] === 'select:post')
            $value = is_array($value)? $value : [];
        return $value;
    }

    public function get($value)
    {
        if (empty($value)) return null;
        $info = explode('.', $value);
        $filename = array_shift($info);

        if (!isset($this->data[$filename])) {
            $data = $this->loadFile($filename);
            $this->data[$filename] = $this->replaceLang($data);
        }

        $result = $this->result($filename, $info);
        return $result;
    }

    public function getAllFiles()
    {
        return File::get_files_by_pattern($this->path, '*.view.php');
    }

    public function getAll()
    {
        $files = File::get_files_by_pattern($this->path, '*.view.php');
        $result = [];
        foreach ($files as $file) {
            $name = File::name($file);
            $name = str_replace('.view', '', $name);
            $result[$name] = self::get($name);
            $result[$name]['key'] = $name;
        }

        return $result;
    }

    private function loadFile($filename)
    {

        $file = $this->path . $filename . '.view.php';
        if (is_file($file)) {
            return (include $file);
        }

        return null;
    }

    private function replaceLang($data)
    {
        $data = var_export($data, true);
        $regex = '/{(.*?)}/m';
        preg_match_all($regex, $data, $matches, PREG_SET_ORDER, 0);
        $replaces = [];
        if (!empty($matches)) {
            foreach ($matches as $item) {
                if (!is_array($item) && count($item) !== 2)
                    continue;

                $key = $item[0];
                if (!isset($replaces[$key])) {
                    $value = trim($item[1]);
                    $value = $this->lang->get($value);
                    $data = str_replace($key, $value, $data);
                    $replaces[$key] = $value;
                }
            }
        }

        $data = 'return ' . $data . ';';
        $data = eval($data);
        return $data;
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