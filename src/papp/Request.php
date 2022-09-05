<?php
namespace appktia\papp;

class Request
{
    public static function method() {
        return filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_ENCODE);
    }

    public static function get($str = '') {
        if (!empty($str)) {
            return isset($_GET[$str]) ? $_GET[$str] : '';
        } else {
            return $_GET;
        }
    }
    public static function post($str = '') {
        if (!empty($str)) {
            return isset($_POST[$str]) ? $_POST[$str] : '';
        } else {
            return $_POST;
        }
    }

    public static function put(string $name = ''){
        $lines = file('php://input');
        $keyLinePrefix = 'Content-Disposition: form-data; name="';

        $PUT = [];
        $findLineNum = null;

        foreach($lines as $num => $line){
            if(strpos($line, $keyLinePrefix) !== false){
                if($findLineNum){ break; }
                if($name !== substr($line, 38, -3)){ continue; }
                $findLineNum = $num;
            } else if($findLineNum){
                $PUT[] = $line;
            }
        }

        array_shift($PUT);
        array_pop($PUT);

        return mb_substr(implode('', $PUT), 0, -2, 'UTF-8');

    }

    public static function patch(string $name = '') {

    }
}