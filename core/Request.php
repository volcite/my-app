<?php

namespace Core;

class Request
{
    /*
    *メソッドがPOSTかどうか判定
    *サーバ変数の中身を取り出す
    */
    public function isPost()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            return true;
        }

        return false;
    }


    /*
    *GET変数から値を取得する
    */
    public function getGet($name, $default=null)
    {
        if(isset($_GET[$name])) {
            return $_GET[$name];
        }

        return $default;
    }

    /*
    *POST変数から値を取得する
    */
    public function getPost($name, $default = null)
    {
        if(isset($_POST[$name])) {
            return $_POST[$name];
        }

        return $default;
    }

    /*
    *ホスト名を取得する
    */
    public function getHost()
    {
        if(!empty($_SERVER['HTTP_HOST'])) {
            return $_SERVER['HTTP_HOST'];
        }

        return $_SERVER['SERVER_NAME'];
    }

    /*
    *httpsでアクセスされたかどうかの判定
    */
    public function isSsl()
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            return true;
        }

        return false;
    }

    /*
    *URL→普段よく目にするやつ（https:以下）、住所
    *URN→web上で対象を特定するための名前
    *URI→URLとURNの総称
    */
    public function getRequestUri()
    {
        //ホスト部分（ex:www.example.com）
        return $_SERVER['REQUEST_URI'];
    }

    /*
    *baseURL(ホスト部より後ろからフロントコントローラまでの値とする)を取得
    */
    public function getBaseUrl()
    {
        $script_name = $_SERVER['SCRIPT_NAME'];

        $request_uri = $this->getRequestUri();

        //strposは第1引数に指定した文字列から第2引数に指定した文字列が最初に出現する位置を調べる
        if (strpos($request_uri, $script_name) === 0) {
            //フロントコントローラがURLに含まれる場合
            return $script_name;
        //dirnameはファイルのパスからディレクトリ部分を抜き出す。今回はフロントコントローラを省略した値が取得できる
        } else if (strpos($request_uri, dirname($script_name)) === 0) {
            //フロントコントローラが省略されている場合
            return rtrim(dirname($script_name), '/');
        }

        return '';
    }

    /*
    *PATH_INFO(ベースURLより後ろの値)を取得
    */
    public function getPathInfo()
    {
        $base_url = $this->getBaseUrl();
        $request_uri = $this->getRequestUri();

        if (($pos = strpos($request_uri, '?')) !== false) {
            $request_uri = substr($request_uri, 0, $pos);
        }

        $path_info = (string)substr($request_uri, strlen($base_url));
        return $path_info;
    }
}