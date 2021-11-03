<?php

namespace App\Model;

class UserManager extends AbstractManager
{
    public const TABLE = 'user';

    public function registerUser(array $user): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (nickname, email, password) 
        VALUES (:nickname, :email, :password)");
        $statement->bindValue(':nickname', $user['nickname'], \PDO::PARAM_STR);
        $statement->bindValue(':email', $user['email'], \PDO::PARAM_STR);
        // $statement->bindValue(':password', password_hash($user['password'], PASSWORD_BCRYPT), \PDO::PARAM_STR);
        $statement->bindValue(':password', $user['password'], \PDO::PARAM_STR);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    public function update(array $user): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET 
        nickname = :nickname AND password = :password AND email = :email WHERE id=:id");
        $statement->bindValue(':nickname', $user['nickname'], \PDO::PARAM_STR);
        $statement->bindValue(':password', $user['password'], \PDO::PARAM_STR);
        $statement->bindValue(':email', $user['email'], \PDO::PARAM_STR);
        $statement->bindValue(':id', $user['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function login(array $user): bool
    {
        $statement = $this->pdo->prepare('SELECT * FROM user WHERE email=:email AND password=:password');
        $statement->bindValue(':email', $user['email'], \PDO::PARAM_STR);
        $statement->bindValue(':password', $user['password'], \PDO::PARAM_STR);

        return $statement->execute();
        
    }
}
