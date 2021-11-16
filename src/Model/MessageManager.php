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

    /**
     * Get all row from database.
     */
    public function selectAllMessageForOnePost(int $idPost, string $orderBy = '', string $direction = ''): array
    {
        $query = 'SELECT us.nickname,
        us.picture_link,
        me.message,
        me.date
        FROM ' . static::TABLE . ' as me JOIN
        user as us ON me.user_id = us.id WHERE
        post_id = :idpost';
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('idpost', $idPost, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
