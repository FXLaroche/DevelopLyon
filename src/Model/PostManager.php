<?php

namespace App\Model;

use App\Model\AbstractManager;

class PostManager extends AbstractManager
{
    public const TABLE = 'post';

    public function create(array $postData): int
    {
        $query = "INSERT INTO " . self::TABLE . "(subject, user_id, theme_id, message, keyword) 
            VALUES(:subject, :user_id, :theme_id, :message, :keyword)";
        $statement = $this->pdo->prepare($query);

        $statement->bindValue(':subject', $postData['subject'], \PDO::PARAM_STR);
        $statement->bindValue(':user_id', $postData['user_id'], \PDO::PARAM_STR);
        $statement->bindValue(':theme_id', $postData['theme_id'], \PDO::PARAM_INT);
        $statement->bindValue(':message', $postData['message'], \PDO::PARAM_STR);
        $statement->bindValue(':keyword', $postData['keyword'], \PDO::PARAM_STR);

        $statement->execute();

        return (int)$this->pdo->lastInsertId();
    }


    public function edit(array $postData)
    {
        $query = "UPDATE " . self::TABLE . " SET subject=:subject, 
        message=:message, 
        keyword=:keyword,
        theme_id=:theme_id
         WHERE id = :id;";
        $statement = $this->pdo->prepare($query);

        $statement->bindValue(':subject', $postData['subject'], \PDO::PARAM_STR);
        $statement->bindValue(':id', $postData['id'], \PDO::PARAM_STR);
        $statement->bindValue(':theme_id', $postData['theme_id'], \PDO::PARAM_STR);
        $statement->bindValue(':message', $postData['message'], \PDO::PARAM_STR);
        $statement->bindValue(':keyword', $postData['keyword'], \PDO::PARAM_STR);

        $statement->execute();
    }
    /**
     * Search post in database
     */
    public function search(string $search): array
    {
        $search = "%" . $search . "%";
        $statement = $this->pdo->prepare("SELECT po.id, us.picture_link,
         us.nickname,
         po.subject,
         po.date, 
         count(me.id) AS numberMessage FROM " . self::TABLE . " AS po JOIN user AS us ON po.user_id = us.id
         LEFT JOIN message AS me ON me.post_id = po.id
         WHERE keyword LIKE :search OR subject LIKE :search
         GROUP BY po.id");
        $statement->bindValue('search', $search, \PDO::PARAM_STR);

        $statement->execute();
        return $statement->fetchAll();
    }

    public function selectPostTreeData(int $postId)
    {
        $query = "SELECT t.name theme_name, t.id theme_id, c.name cat_name, c.id category_id 
        FROM theme t 
        JOIN post p ON p.theme_id=t.id 
        JOIN category c ON c.id=t.category_id 
        WHERE p.id=:postId;";

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':postId', $postId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }


    /**
     * Get all row from database.
     */
    public function selectAllById(int $idCategory, string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT th.name,
        th.id as theme_id,
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

    /**
     * Get one row from database by ID.
     *
     */
    public function selectOnePostById(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT us.nickname,
        us.id as user_id,
        us.picture_link,
        po.id as post_id,
        po.subject,
        po.date,
        po.message FROM " . static::TABLE . " AS po JOIN user AS us ON po.user_id = us.id WHERE po.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }
}
