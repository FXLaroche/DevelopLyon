<?php

namespace App\Model;

class SearchManager extends AbstractManager
{
    public const TABLE = 'search';

    /**
     * Insert new search in database
     */
    public function insert(array $search): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " 
        (`word`, date_last, nb_searched) VALUES (:word, :date_last, :nb_searched)");
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
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . "
         SET date_last = :date_last, nb_searched = :nb_searched WHERE id=:id");
        $statement->bindValue('id', $search['id'], \PDO::PARAM_INT);
        $statement->bindValue('date_last', $search['date_last'], \PDO::PARAM_STR);
        $statement->bindValue('nb_searched', $search['nb_searched'], \PDO::PARAM_INT);

        return $statement->execute();
    }
}
