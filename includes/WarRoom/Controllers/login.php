<?php
namespace WarRoom\Controllers;

use \WarRoom\Models;

/**
 * Allows users to login
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) 2014 Tyler Menezes. Released under the Perl Artistic License 2.0.
 *
 * @package     WarRoom\Controllers
 */
class login
{
    use \CuteControllers\Controller;

    public function get_index()
    {
        echo \WarRoom::$twig->render('login.html.twig');
    }

    public function post_index()
    {
        $oauth_url = "https://www.facebook.com/dialog/oauth?"
                    ."client_id=".\WarRoom::$config->facebook->public
                    ."&redirect_uri=".\CuteControllers\Router::link('/login/oauth?r='.rand(0,1000), true)
                    ."&scope=email";
        $this->redirect($oauth_url);
    }

    public function get_email()
    {
        echo \WarRoom::$twig->render('login_email.html.twig');
    }

    public function post_email()
    {
        try {
            $user = Models\User::find()->where('email = ?', $this->request->post('email'))->one();
        } catch (\TinyDb\NoRecordException $ex) {
            $user = new Models\User([
                'email' => $this->request->post('email'),
                'first_name' => $this->request->post('first_name'),
                'last_name' => $this->request->post('last_name')
            ]);
        }

        $user->login();
        $this->redirect('/campaigns');
    }

    public function get_oauth()
    {
        $code = $this->request->get('code');
        $request_url = "https://graph.facebook.com/oauth/access_token?"
                       ."client_id=".\WarRoom::$config->facebook->public
                       ."&redirect_uri=".\CuteControllers\Router::link('/login/oauth?r='.$this->request->get('r'), true)
                       ."&client_secret=".\WarRoom::$config->facebook->secret
                       ."&code=".$code;
        $response = [];
        parse_str(file_get_contents($request_url), $response);
        $access_token = $response['access_token'];

        $graph_url = "https://graph.facebook.com/me?access_token=".$access_token;
        $graph = json_decode(file_get_contents($graph_url));

        try {
            $user = Models\User::find()->where('email = ?', $graph->email)->one();
        } catch (\TinyDb\NoRecordException $ex) {
            $user = new Models\User([
                'email' => $graph->email,
                'first_name' => $graph->first_name,
                'last_name' => $graph->last_name
            ]);
        }

        $user->login();
        $this->redirect('/campaigns');
    }
}