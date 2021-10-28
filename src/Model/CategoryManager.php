<?php

namespace App\Model;

class CategoryManager extends AbstractManager
{
    public const TABLE = 'category';

    /**
     * Insert new category in database
     */
    public function insert(array $category): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (name, picture_link)
         VALUES (:name, :picture_link)");
        $statement->bindValue('name', $category['name'], \PDO::PARAM_STR);
        $statement->bindValue('picture_link', $category['picture_link'], \PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update category in database
     */
    public function update(array $category): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET
         name = :name, picture_link = :picture_link WHERE id=:id");
        $statement->bindValue('id', $category['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $category['name'], \PDO::PARAM_STR);
        $statement->bindValue('picture_link', $category['picture_link'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}
