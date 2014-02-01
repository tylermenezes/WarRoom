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

    public function before_is_logged_in()
    {
        if (Models\User::is_logged_in()) {
            $this->redirect('/campaigns');
        }
    }

    public function get_index()
    {
        echo \WarRoom::$twig->render('login.html.twig');
    }

    public function post_index()
    {
        try {
            $user = Models\User::find()->where('email = ?', $this->request->post('email'))->one();

            /*if (!$user->check_password($this->request->post('password'))) {
                throw new \TinyDb\NoRecordException();
            }*/
        } catch (\TinyDb\NoRecordException $ex) {
            echo \WarRoom::$twig->render('login.html.twig', ['nologin' => true]);
            exit;
        }

        $user->login();
        $this->redirect('/campaigns');
    }

    public function get_invite()
    {
        $this->check_invite();
    }

    private function check_invite()
    {
        try {
            Models\Invite::find()
                ->where('inviteID = ?', $this->request->param('code'))
                ->where('used_at IS NULL')
                ->one();
        } catch (\TinyDb\NoRecordException $ex) {
            throw new \CuteControllers\HttpError(404);
        }
    }
}