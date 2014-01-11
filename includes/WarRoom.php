<?php

include_once(implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), 'submodules', 'Jetpack', 'Jetpack', 'App.php']));

class WarRoom extends \Jetpack\App {
    public static function after()
    {
        if (!is_cli()) {
            // User Login Check
            if (\WarRoom\Models\User::is_logged_in()) {
                static::$twig->addGlobal('me', \WarRoom\Models\User::me());
            }
        }
    }
}