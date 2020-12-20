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

use pinoox\app\com_pinoox_paper\component\setting\View;
use pinoox\component\File;
use pinoox\component\HelperString;

class Setting
{
    private static $obj = null;
    private $data = [];
    private $path = null;
    private $view = null;

    public function __construct($path)
    {
        $this->setPath($path);
        $this->view = View::init($path);
    }

    private function setPath($path)
    {
        if (!HelperString::lastHas($path, DIRECTORY_SEPARATOR))
            $path .= DIRECTORY_SEPARATOR;

        $this->path = $path . 'config' . DIRECTORY_SEPARATOR;
    }

    public static function init($path)
    {
        self::$obj = new Setting($path);

        return self::$obj;
    }

    public function remove($key)
    {
        $this->push($key, null, 'del');

        return self::$obj;
    }

    private function push($key, $value, $type = 'add')
    {
        $keys = explode('.', $key);
        $filename = $keys[0];


        if (isset($this->data[$filename]) && $type == 'reset')
            unset($this->data[$filename]);

        if ($type == 'reset') return;

        if (!isset($this->data[$filename])) {

            $this->data[$filename] = $this->loadFile($filename);
        }

        $temp = &$this->data;

        $countKeys = count($keys) - 1;
        $key = null;
        for ($i = 0; $i <= $countKeys; $i++) {
            $key = $keys[$i];
            if (($i != $countKeys)) {
                if (!isset($temp[$key]) || !is_array($temp[$key]))
                    $temp[$key] = [];

                $temp = &$temp[$key];
            }
        }

        if ($type == 'add') {
            if (!isset($temp[$key])) {
                $temp[$key] = [$value];
            } else {
                if (!is_array($temp[$key]))
                    $temp[$key] = [$temp[$key]];
                $temp[$key][] = $value;
            }
        } else if ($type == 'set') {
            $temp[$key] = $value;
        } else if ($type == 'del') {
            unset($temp[$key]);
        }
    }

    private function loadFile($filename)
    {
        $file = $this->path . $filename . '.config.php';

        if (!is_file($file)) {
            $data = $this->view->getValues($filename);
            $this->createData($filename, $data);
        }

        if (is_file($file)) {
            return (include $file);
        }

        return null;
    }

    private function createData($filename, $data)
    {
        $file = $this->path . $filename . '.config.php';

        if (!is_file($file)) {
            $this->data[$filename] = $data;
            $this->save($filename);
        }
    }

    public function save($name)
    {
        if (empty($name) || HelperString::has($name, '.')) return;
        $data = self::get($name);

        $file = $this->path . $name . '.config.php';

        $data_for_save = '<?' . 'php' . "\n";
        $data_for_save .= '//pinoox config file, generated at "' . gmdate('Y-m-d H:i') . "\"\n\n";
        $data_for_save .= 'return ' . var_export($data, true) . ";\n\n//end of config";

        File::generate($file, $data_for_save);
    }

    public function get($value)
    {
        if (empty($value)) return $value;
        $info = explode('.', $value);
        $filename = array_shift($info);

        if (!isset($this->data[$filename])) {
            $this->data[$filename] = $this->loadFile($filename);
        }

        $result = $this->result($filename, $info);
        return $result;
    }

    public function getAll()
    {
        $files = $this->view->getAllFiles();
        $result = [];
        foreach ($files as $file) {
            $name = File::name($file);
            $name = str_replace('.view', '', $name);
            $result[$name] = self::get($name);
        }

        return $result;
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

    public function add($key, $value)
    {
        $this->push($key, $value, 'add');

        return self::$obj;
    }

    public function reset($key)
    {
        $this->push($key, null, 'reset');

        return self::$obj;
    }

    public function set($key, $value)
    {
        $this->push($key, $value, 'set');

        return self::$obj;
    }

    /**
     * @return View
     */
    public function view()
    {
        return $this->view;
    }
}