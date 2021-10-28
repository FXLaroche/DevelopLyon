<?php

namespace App\Model;

use App\Model\AbstractManager;

class PostManager extends AbstractManager
{
    public const TABLE = 'post';

    public function create(array $postData): int
    {
        $query = "INSERT INTO " . self::TABLE . "(subject, user_id, theme_id, message) 
            VALUES(:subject, (SELECT id FROM user WHERE nickname=:nickname), 
            (SELECT id FROM theme WHERE name=:theme_name), :message)";
        $statement = $this->pdo->prepare($query);

        $statement->bindValue(':subject', $postData['subject'], \PDO::PARAM_STR);
        $statement->bindValue(':nickname', $postData['nickname'], \PDO::PARAM_STR);
        $statement->bindValue(':theme_name', $postData['theme_name'], \PDO::PARAM_STR);
        $statement->bindValue(':message', $postData['message'], \PDO::PARAM_STR);

        $statement->execute();

        return (int)$this->pdo->lastInsertId();
    }
}
