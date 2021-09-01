<?php

namespace Core;

use PDO;
use Models\UserRepository;
use Models\ContentRepository;
use Models\FollowingRepository;
use Models\LikeRepository;


class DbManager
{
    protected $connections = array();
    protected $repository_connection_map = array();
    protected $repositories = array();

    /*
    *DBと接続する
    */
    public function connect($name, $params)
    {
        $params = array_merge(array(
            'dsn' => null,
            'user' => '',
            'password' => '',
            'options' => array(),
        ), $params);

        $con = new PDO(
            $params['dsn'],
            $params['user'],
            $params['password'],
            $params['options']
        );

        //内部でエラーが出た際に例外を発生させる指定
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->connections[$name] = $con;
    }

    /*
    *接続したコネクションを取得する
    */
    public function getConnection($name = null)
    {
        if(is_null($name)) {
            //名前の指定がされなかった場合PDOクラスのインスタンスを返す
            return current($this->connections);
        }
        return $this->connections['name'];
    }

    /*
    *接続する接続名とRepositoryクラスの対応を格納
    */
    public function setRepositoryConnectionMap($repository_name, $name)
    {
        $this->repository_connection_map[$repository_name] = $name;
    }

    /*
    *接続する接続名とRepositoryクラスの対応を取得
    */
    public function getConnectionForRepository($repository_name)
    {
        if(isset($htis->repository_connection_map[$repository_name])) {
            $name = $this->repository_connection_map[$repository_name];
            $con = $this->getConnection($name);
        } else {
            $con = $this->getConnection();
        }
        return $con;
    }

    /*
    *Repositoryクラスのインスタンスを生成
    */
    public function get($repository_name)
    {
        if (!isset($this->repositories[$repository_name])) {
            //コネクション取得
            $con = $this->getConnectionForRepository($repository_name);
            switch ($repository_name) {
                case 'User':
                    $repository = new UserRepository($con);
                    break;
                case 'Content':
                    $repository = new ContentRepository($con);
                    break;
                case 'Following':
                    $repository = new FollowingRepository($con);
                    break;
                case 'Like':
                    $repository = new LikeRepository($con);
                    break;
            }
            //インスタンスを保持するため格納
            $this->repositories[$repository_name] = $repository;
        }
        return $this->repositories[$repository_name];
    }

    /*
    *インスタンスを破棄すると自動で行われる
    *接続を開放する（変数を破棄）
    */
    public function __distruct()
    {
        foreach ($this->repositories as $repository) {
            unset($repository);
        }

        foreach ($this->connections as $con) {
            unset($con);
        }
    }
}