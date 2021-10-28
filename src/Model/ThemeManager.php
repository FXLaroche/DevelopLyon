<?php

namespace App\Model;

class ThemeManager extends AbstractManager
{
    public const TABLE = 'theme';

    /**
     * Insert new theme in database
     */
    public function insert(array $theme): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (name, category_id)
         VALUES (:name, :category_id)");
        $statement->bindValue('name', $theme['name'], \PDO::PARAM_STR);
        $statement->bindValue('category_id', $theme['category_id'], \PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update theme in database
     */
    public function update(array $theme): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET
         name = :name, category_id = :category_id WHERE id=:id");
        $statement->bindValue('id', $theme['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $theme['name'], \PDO::PARAM_STR);
        $statement->bindValue('category_id', $theme['category_id'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}
