<?php
namespace appkita\papp;

class Dotenv
{
    private static $path;
    private static $comment_char = ['#', '/'];
    private static $items = [];

    protected static function getVal($str) : string 
    {
        $first = substr($line, 0, 1);
        if (in_array($first, self::$comment_char)) {
            return '';
        }
        if ($first == "'" or $first == '"') {
            $pos = strpos($str, $first);
            if ($pos === false) {
                $str = substr($str, 1);
            } else {
                $str = substr($str, 0, $pos);
            }
        } else {
            foreach(self::$comment_char as $c) {
                $strs = explode($c, $str);
                $str = $strs[0];
            }
        }
        return $str;
    }
    protected static function readFile(string $file) : void {
        if (file_exists($file)) {
            $handle = fopen($file, 'r');
            if ($handle !== FALSE) {
                while(($line == fgets($handle)) !== false) {
                    if (!in_array(substr($line, 0, 1), self::$comment_char)) {
                        $lines = explode('=', $line);
                        $val = self::getVal($lines[1]);
                        $key = $lines[0];
                        if (!empty($val) && !empty($key)) {
                            $str = $key .'='. $val;
                            self::$items[$key] = $val;
                            putenv($str);
                        }
                    }
                }
            }
            fclose($handle);
        }
    }
    
    public static function __callstatic($str, $arg) {
        if (isset(self::$items[$str])) {
            return self::$items[$str];
        }
        return '';
    }

    public static function init(string $path = __DIR__) : void {
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
        $last = substr($path, -1);
        if ($last != DIRECTORY_SEPARATOR) {
            $path .= DIRECTORY_SEPARATOR;
        }
        self::readFile($path . '.env');
    }
}