<?php

namespace Core;

use PDO;

abstract class DbRepository
{
    protected $con;

    public function __construct($con)
    {
        $this->setConnection($con);
    }

    public function setConnection($con)
    {
        $this->con = $con;
    }

    public function execute($sql, $params = array())
    {
        //プリペアードステートメントでSQLインジェクションを防ぐ、SQL文をセットする
        $stmt = $this->con->prepare($sql);
        //パラメータをセットして実行する
        $stmt->execute($params);
        return $stmt;
    }

    public function fetch($sql, $params = array())
    {
        //PDO::FETCH_ASSOCは連想配列で受けとるという指定
        return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll($sql, $params = array())
    {
        return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
}