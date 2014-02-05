<?php

include_once(implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), 'submodules', 'Jetpack', 'Jetpack', 'App.php']));

class WarRoom extends \Jetpack\App {
    public static function before()
    {
        // Increase session timeout
        $session_timeout = 60*60*24*31;
        $session_dir = pathify(sys_get_temp_dir(), '.warroom_'.basename(\WarRoom::$config->domain).'_sessions');
        session_set_cookie_params($session_timeout);
        ini_set('session.gc_maxlifetime',$session_timeout);
        ini_set('session.save_path', $session_dir);

        if (!is_dir($session_dir)) {
            mkdir($session_dir);
        }

        session_start();
    }

    public static function after()
    {
        if (!is_cli()) {
            if (!static::$config->email_domain) {
                static::$config->email_domain = \CuteControllers\Request::current()->host;
            }

            static::$config->domain = \CuteControllers\Request::current()->host;

            static::$twig->addGlobal('domain', static::$config->domain);

            // User Login Check
            if (\WarRoom\Models\User::is_logged_in()) {
                static::$twig->addGlobal('me', \WarRoom\Models\User::me());
            }
        }
    }
}