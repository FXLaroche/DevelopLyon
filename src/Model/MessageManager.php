<?php

namespace App\Model;

class MessageManager extends ItemManager
{
    public const TABLE = 'message';

    public function insert(array $message): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (user_id, post_id, date, message) 
        VALUES (:user_id, :post_id, :date, :message)");

        $statement->bindValue('user_id', $message['user_id'], \PDO::PARAM_INT);
        $statement->bindValue('post_id', $message['post_id'], \PDO::PARAM_INT);
        $statement->bindValue('date', $message['date'], \PDO::PARAM_INT);
        $statement->bindValue('message', $message['message'], \PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
