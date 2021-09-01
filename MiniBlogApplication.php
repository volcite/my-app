<?php

use Core\Application;

class MiniBlogApplication extends Application
{
    protected $login_action = array('account', 'signin');

    public function getRouteDir()
    {
        return dirname(__FILE__);
    }

    protected function registerRoutes()
    {
        return array(
            '/' => array('controller' => 'content', 'action' => 'index'),
            '/content/post' => array('controller' => 'content', 'action' => 'post'),
            '/allUser' => array('controller' => 'user', 'action' => 'allUser'),
            '/like' => array('controller' => 'like', 'action' => 'like'),
            '/unlike' => array('controller' => 'like', 'action' => 'unlike'),
            '/user/:user_name' => array('controller' => 'user', 'action' => 'show'),
            '/edit/:user_name' => array('controller' => 'user', 'action' => 'edit'),
            '/update/user' => array('controller' => 'user', 'action' => 'update'),
            '/delete/user' => array('controller' => 'user', 'action' => 'delete'),
            'user/:user_name/content/:id' => array('controller' => 'content', 'action' => 'show'),
            '/user' => array('controller' => 'user', 'action' => 'index'),
            '/account/:action' => array('controller' => 'account'),
            '/follow' => array('controller' => 'following', 'action' => 'follow'),
            '/unfollow' => array('controller' => 'following', 'action' => 'unfollow'),
        );
    }

    protected function configure()
    {
        $this->db_manager->connect('master', array(
            'dsn' => 'mysql:host=db;dbname=laravel',
            'user' => 'root',
            'password' => 'password'
        ));
    }
}