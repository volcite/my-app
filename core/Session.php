<?php

namespace Core;

class Session
{
    protected static $sessionStarted = false;
    protected static $sessionIdRegenerated = false;

    public function __construct()
    {
        //Sessionが開始されていなければセッション開始
        if(!self::$sessionStarted) {
            session_start();
            self::$sessionStarted = true;
        }
    }

    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    //セッション情報を取得する
    public function get($name, $default = null)
    {
        if(isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
        return $default;
    }

    public function remove($name)
    {
        unset($_SESSION[$name]);
    }

    public function clear()
    {
        $_SESSION = array();
    }

    /*
    *セッションID生成
    */
    public function regenerate($destroy = true)
    {
        if(!self::$sessionIdRegenerated) {
            session_regenerate_id($destroy);
            self::$sessionIdRegenerated = true;
        }
    }

    public function setAuthenticated($bool)
    {
        $this->set('_authenticated', (bool)$bool);
        //ログインのセッションＩＤが固定されないよう再生成
        $this->regenerate();
    }

    public function isAuthenticated()
    {
        return $this->get('_authenticated', false);
    }
}