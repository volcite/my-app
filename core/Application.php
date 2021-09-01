<?php

namespace Core;

abstract class Application
{
    protected $debug = false;
    protected $request;
    protected $response;
    protected $session;
    protected $db_manager;
    protected $login_action = array();

    public function __construct($debug = false)
    {
        $this->setDebugMode($debug);
        $this->initialize();
        $this->configure();
    }

    /*
    *デバックモードに応じてエラー表示処理を変更
    */
    protected function setDebugMode($debug)
    {
        if ($debug) {
            $this->debug = true;
            ini_set('display_errors', 1);
            error_reporting(-1);
        } else {
            $this->debug = false;
            ini_set('display_errors', 0);
        }
    }
    
    /*
    *クラスを初期化する
    */
    protected function initialize()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->db_manager = new DbManager();
        $this->router = new Router($this->registerRoutes());
    }

    protected function configure()
    {

    }

    abstract public function getRouteDir();

    abstract protected function registerRoutes();

    public function isDebugMode()
    {
        return $this->debug;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function getDbManager()
    {
        return $this->db_manager;
    }

    public function getControllerDir()
    {
        return $this->getRouteDir() . '/controllers';
    }

    public function getViewDir()
    {
        return $this->getRouteDir() . '/views';
    }

    public function getModelDir()
    {
        return $this->getRouteDir() . '/models';
    }

    public function getWeDir()
    {
        return $this->getRouteDir() . '/web';
    }

    /*
    *ルーティングパラメータを取得してコントローラ名とアクション名を取得する
    */
    public function run()
    {
        try {
            $params = $this->router->resolve($this->request->getPathInfo());
            if ($params === false) {
                throw new HttpNotFoundException('No route found for' . $this->request->getPathInfo());
            }
            $controller = $params['controller'];
            $action = $params['action'];

            $this->runAction($controller, $action, $params);
        } catch (HttpNotFoundException $e) {
            $this->render404Page($e);
        } catch (UnauthorizedActionException $e) {
            list($contoroller, $action) = $this->login_action;
            $this->runAction('account', $action);
        }

        $this->response->send();
    }

    /*
    *コントローラのアクションを実行する
    */
    public function runAction($controller_name, $action, $params = array())
    {
        //文字列の最初の文字を大文字にする
        $controller_class = ucfirst($controller_name) . 'Controller';
        $controller = $this->findController($controller_class);
        if($controller === false) {
            throw new HttpNotFoundException($controller_class . ' controller is not found.');
        }
        $content = $controller->run($action, $params);
        $this->response->setContent($content);
    }

    /*
    *クラスファイルを読み込み、コントローラクラスを生成
    */
    protected function findController($controller_class)
    {
        //クラスが定義済みか判定
        if(!class_exists($controller_class)) {
            $controller_file = $this->getControllerDir() . '/' . $controller_class . '.php';
            if(!is_readable($controller_file)) {
                return false;
            } else {
                require_once $controller_file;
                if(!class_exists($controller_class)) {
                    return false;
                }
            }
        }
        return new $controller_class($this);
    }

    protected function render404Page($e)
    {
        $this->response->setStatusCode(404, 'Not Found');
        $message = $this->isDebugMode() ? $e->getMessage() : 'Page not Found';
        $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

        $this->response->setContent(
            <<<EOF
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>404</title>
                </head>
                <body>
                    {$message}
                </body>
                </html> 
            EOF
        );
    }
}