<?php

namespace Models;

use Core\DbRepository;
use DateTime;

class UserRepository extends DbRepository
{
    public function insert($user_name, $password)
    {
        $password = $this->hashPassword($password);
        $now = new DateTime();

        $sql = "INSERT INTO user(user_name, password, created_at) VALUES (:user_name, :password, :created_at)";
        $stmt = $this->execute($sql, array(
            ':user_name' => $user_name,
            ':password' => $password,
            ':created_at' => $now->format('Y-m-d H:i:s'),
        ));
    }

    public function update($id, $user_name, $password)
    {
        $password = $this->hashPassword($password);
        $now = new DateTime();

        $sql = "UPDATE user SET user_name = :user_name, password = :password, created_at = :created_at WHERE id = :id";
        $stmt = $this->execute($sql, array(
            ':id' => $id,
            ':user_name' => $user_name,
            ':password' => $password,
            ':created_at' => $now->format('Y-m-d H:i:s'),
        ));
    }

    public function delete($id)
    {
        $sql = "
            DELETE contents, user FROM user
                LEFT JOIN contents ON user.id = contents.user_id 
                    WHERE user.id = :id
        ";
        $stmt = $this->execute($sql, array(
            ':id' => $id,
        ));
    }

    public function hashPassword($password)
    {
        return sha1($password . 'SecretKey');
    }

    public function fetchByUserName($user_name)
    {
        $sql = "SELECT * FROM user WHERE user_name = :user_name";
        return $this->fetch($sql, array(':user_name' => $user_name));
    }

    public function isUniqueUserName($user_name)
    {
        $sql = "SELECT COUNT(id) as count FROM user WHERE user_name = :user_name";
        $row = $this->fetch($sql, array(':user_name' => $user_name));
        if($row['count'] === '0') {
            return true;
        }
        return false;
    }

    public function isUniqueUserNameEdit($user_name, $id)
    {
        $sql = "SELECT COUNT(id) as count FROM user WHERE user_name = :user_name && NOT id = :id";
        $row = $this->fetch($sql, array(
            ':user_name' => $user_name,
            'id' => $id));
        if($row['count'] === '0') {
            return true;
        }
        return false;
    }

    public function fetchAllFollowingsByUserId($user_id)
    {
        $sql = "
            SELECT u.* FROM user u
                LEFT JOIN following f ON f.following_id = u.id
            WHERE f.user_id = :user_id
        ";

        return $this->fetchAll($sql, array(':user_id' => $user_id));
    }

    public function fetchAllUser()
    {
        $sql = "SELECT id, user_name FROM user";
        return $this->fetchAll($sql);
    }
}