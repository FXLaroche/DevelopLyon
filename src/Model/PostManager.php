<?php

namespace App\Model;

class PostManager extends AbstractManager
{
    public const TABLE = 'post';

    /**
     * Search post in database
     */
    public function search(string $search): array
    {
        $search = "%" . $search . "%";
        $statement = $this->pdo->prepare("SELECT user.picture_link,
         user.nickname,
         post.subject,
         post.date FROM " . self::TABLE . " JOIN user ON post.user_id = user.id
         WHERE keyword LIKE :search OR subject LIKE :search");
        $statement->bindValue('search', $search, \PDO::PARAM_STR);

        $statement->execute();
        return $statement->fetchAll();
    }
}
