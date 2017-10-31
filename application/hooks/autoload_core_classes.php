<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('autoload_core_classes')){
    function autoload_core_classes($class) {
        if (file_exists(APPPATH . 'core/' . $class . '.php')) {
            require_once APPPATH . 'core/' . $class . '.php';
        }
    }
}

spl_autoload_register('autoload_core_classes');
