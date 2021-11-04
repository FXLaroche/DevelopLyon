<?php

namespace App\Model;

class PostManager extends AbstractManager
{
    public const TABLE = 'search';

    /**
     * Insert new search in database
     */
    public function insert(array $search): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`word`) VALUES (:word)");
        $statement->bindValue('word', $search['word'], \PDO::PARAM_STR);
        $statement->bindValue('date_last', $search['date_last'], \PDO::PARAM_STR);
        $statement->bindValue('nb_searched', $search['nb_searched'], \PDO::PARAM_INT);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update search in database
     */
    public function update(array $search): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `title` = :title WHERE id=:id");
        $statement->bindValue('id', $search['id'], \PDO::PARAM_INT);
        $statement->bindValue('word', $search['word'], \PDO::PARAM_STR);
        $statement->bindValue('date_last', $search['date_last'], \PDO::PARAM_STR);
        $statement->bindValue('nb_searched', $search['nb_searched'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    /**
     * Search search in database
     */
    public function search(string $search): array
    {
        $search = "%" . $search . "%";
        $statement = $this->pdo->prepare("SELECT word FROM " . self::TABLE . "
         WHERE word LIKE :search OR subject LIKE :search");
        $statement->bindValue('search', $search, \PDO::PARAM_STR);

        $statement->execute();
        return $statement->fetchAll();
    }
}
