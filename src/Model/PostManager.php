<?php

namespace App\Model;

use App\Model\AbstractManager;

class PostManager extends AbstractManager
{
    public const TABLE = 'post';

    public function create(array $postData, int $themeId): int
    {
        $query = "INSERT INTO " . self::TABLE . "(subject, user_id, theme_id, message, keyword) 
            VALUES(:subject, :user_id, :theme_id, :message, :keyword)";
        $statement = $this->pdo->prepare($query);

        $statement->bindValue(':subject', $postData['subject'], \PDO::PARAM_STR);
        $statement->bindValue(':user_id', $postData['user_id'], \PDO::PARAM_STR);
        $statement->bindValue(':theme_id', $themeId, \PDO::PARAM_INT);
        $statement->bindValue(':message', $postData['message'], \PDO::PARAM_STR);
        $statement->bindValue(':keyword', $postData['keyword'], \PDO::PARAM_STR);

        $statement->execute();

        return (int)$this->pdo->lastInsertId();
    }
}
