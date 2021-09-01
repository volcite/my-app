<?php

namespace Core;

class Response
{
    protected $content;
    protected $status_code = 200;
    protected $status_text = 'OK';
    protected $http_headers = array();

    /*
    *レスポンスの送信を行う
    */
    public function send()
    {
        //HTTPヘッダを送信する
        header('HTTP/1.1　' . $this->status_code . ' ' . $this->status_text);
        foreach ($this->http_headers as $name => $value) {
            header($name . ': ' . $value);
        }
        echo $this->content;
    }

    /*
    *クライアントに返すHTMLなどを格納
    */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /*
    *HTTPのステータスコードを格納
    */
    public function setStatusCode($status_code, $status_text = '')
    {
        $this->status_code = $status_code;
        $this->status_text = $status_text;
    }

    /*
    *HTTPヘッダを連想配列形式で格納する
    */
    public function setHttpHeader($name, $value)
    {
        $this->http_headers[$name] = $value;
    }
}