<?php

namespace Core;

class Router
{
    protected $routes;

    public function __construct($definitions)
    {
        $this->routes = $this->compileRoutes($definitions);
    }

    public function compileRoutes($definitions)
    {
        $routes = array();

        foreach ($definitions as $url => $params) {
            $tokens = explode('/', trim($url, '/'));
            foreach ($tokens as $i => $token) {
                if(strpos($token, ':') === 0) {
                    //文字の1文字目以降を返す
                    $name = substr($token, 1);
                    $token = '(?P<' . $name . '>[^/]+)';
                }
                $tokens[$i] = $token;
            }
            //配列の要素を連結する
            $pattern = '/' . implode('/' , $tokens);
            $routes[$pattern] = $params;
        }
        return $routes;
    }

    public function resolve($path_info)
    {
        if (substr($path_info, 0, 1) !== '/') {
            $path_info = '/' . $path_info;
        }

        //区切られたルーティングをひとつづつ出す
        foreach ($this->routes as $pattern => $params) {
            //文字列が一致するか正規表現で判定
            if (preg_match('#^' . $pattern . '$#', $path_info, $matches)) {
                //一致したものは繋げていく
                $params = array_merge($params , $matches);
                return $params;
            }
        }

        return false;
    }
}