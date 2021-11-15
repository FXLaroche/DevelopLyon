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
    /**
     * Search post in database
     */
    public function search(string $search): array
    {
        $search = "%" . $search . "%";
        $statement = $this->pdo->prepare("SELECT post.id, user.picture_link,
         user.nickname,
         post.subject,
         post.date FROM " . self::TABLE . " JOIN user ON post.user_id = user.id
         WHERE keyword LIKE :search OR subject LIKE :search");
        $statement->bindValue('search', $search, \PDO::PARAM_STR);

        $statement->execute();
        return $statement->fetchAll();
    }

        /**
     * Get all row from database.
     */
    public function selectAllById(int $idCategory, string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT th.name,
        po.id,
        po.subject,
        count(me.id) AS numberMessage,
        max(me.date) AS lastModify
        FROM ' . static::TABLE . ' as po LEFT JOIN
        message as me ON me.post_id = po.id JOIN
        theme as th ON po.theme_id = th.id WHERE
        theme_id = :idcategory GROUP BY po.id';
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('idcategory', $idCategory, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
