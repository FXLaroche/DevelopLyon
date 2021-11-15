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
}
