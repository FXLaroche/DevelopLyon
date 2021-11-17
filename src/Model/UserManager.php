<?php

namespace App\Model;

use Exception;

class UserManager extends AbstractManager
{
    public const TABLE = 'user';

    public function registerUser(array $user): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (nickname, email, password, 
        role, picture_link) VALUES (:nickname, :email, :password, 
        'utilisateur', 'login.png')");
        $statement->bindValue(':nickname', $user['nickname'], \PDO::PARAM_STR);
        $statement->bindValue(':email', $user['email'], \PDO::PARAM_STR);
        $statement->bindValue(':password', password_hash($user['password'], PASSWORD_BCRYPT), \PDO::PARAM_STR);
        try {
            $statement->execute();
        } catch (\Exception $e) {
            return 0;
        }

        return (int)$this->pdo->lastInsertId();
    }

    public function update(array $user): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET 
        nickname = :nickname, password = :password, email = :email WHERE id = :id");
        $statement->bindValue(':nickname', $user['nickname'], \PDO::PARAM_STR);
        $statement->bindValue(':password', password_hash($user['password'], PASSWORD_BCRYPT), \PDO::PARAM_STR);
        $statement->bindValue(':email', $user['email'], \PDO::PARAM_STR);
        $statement->bindValue(':id', $user['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function deleteAll($ids): void
    {
        $this->pdo->query("DELETE FROM user WHERE id IN ($ids);");
    }

    public function getLoginData(string $email): array
    {
        $query = "SELECT * FROM " . self::TABLE . " WHERE email=:email";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('email', $email, \PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetch();

        if (!$result) {
            throw new Exception("Unknown email: $email", 1);
        }

        return $result;
    }

    public function saveNewImage($file)
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET picture_link='$file' WHERE id=:id");
        $statement->bindValue(':id', $_SESSION['id'], \PDO::PARAM_INT);
        $statement->execute();
    }
}
