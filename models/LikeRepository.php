<?php

namespace Models;

use Core\DbRepository;
use DateTime;

class LikeRepository extends DbRepository
{
    public function insert($user_id, $content_id) {
        $sql = "INSERT INTO likes VALUES (:user_id, :content_id)";
        $stmt = $this->execute($sql, array(
            ':user_id' => $user_id,
            ':content_id' => $content_id,
        ));
    }

    public function delete($user_id, $content_id) {
        $sql = "DELETE FROM likes WHERE user_id = :user_id && content_id = :content_id";
        $stmt = $this->execute($sql, array(
            ':user_id' => $user_id,
            ':content_id' => $content_id,
        ));
    }

    public function fetchAllLikesByUserId($user_id)
    {
        $sql = "
            SELECT c.*, u.user_name FROM contents c
                LEFT JOIN likes l ON l.content_id = c.id
                LEFT JOIN user u ON u.id = c.user_id
            WHERE l.user_id = :user_id
        ";

        return $this->fetchAll($sql, array(':user_id' => $user_id));
    }

    public function isLike($user_id, $content_id) {
        $sql = "SELECT COUNT(user_id) as count FROM likes WHERE user_id = :user_id && content_id = :content_id";
        $row = $this->fetch($sql, array(
            ':user_id' => $user_id,
            ':content_id' => $content_id,
        ));

        if ($row['count'] !== '0') {
            return true;
        }
        return false;
    }

}