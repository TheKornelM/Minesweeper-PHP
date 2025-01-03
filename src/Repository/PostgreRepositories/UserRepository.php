<?php

namespace Repository\PostgreRepositories;

use Repository\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    private $pdo;

    function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function isValidCredentials($email, $password): bool
    {
        $stmt = $this->pdo->prepare("SELECT password FROM userdata WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return false;
        }

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $db_password = $row['password'];

        return $password === $db_password;
    }

    public function registerUser(string $username, string $email, string $password): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO userdata (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        return $stmt->execute();
    }

    public function emailExists(string $email): bool
    {
        $checkEmailStmt = $this->pdo->prepare("SELECT email FROM userdata WHERE email = :email");
        $checkEmailStmt->bindParam(':email', $email);
        $checkEmailStmt->execute();

        return $checkEmailStmt->rowCount() > 0;
    }

    public function usernameExists(string $username): bool
    {
        $checkUsername = $this->pdo->prepare("SELECT username FROM userdata WHERE username = :username");
        $checkUsername->bindParam(':username', $username);
        $checkUsername->execute();

        return $checkUsername->rowCount() > 0;
    }
}