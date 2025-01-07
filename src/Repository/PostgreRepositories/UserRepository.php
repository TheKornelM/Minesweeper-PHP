<?php

namespace Repository\PostgreRepositories;

use Repository\Interfaces\UserRepositoryInterface;

/**
 * UserRepository: A repository class for managing user-related database operations.
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * @var \PDO $databaseConnection The PDO database connection instance.
     */
    private $databaseConnection;

    /**
     * Constructor to initialize the UserRepository with a PDO connection.
     *
     * @param \PDO $databaseConnection The PDO database connection instance.
     */
    public function __construct(\PDO $databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }

    public function isValidCredentials(string $username, string $password): bool
    {
        $query = "SELECT password FROM userdata WHERE username = :username";
        $statement = $this->databaseConnection->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->execute();

        if ($statement->rowCount() === 0) {
            return false;
        }

        $userRecord = $statement->fetch(\PDO::FETCH_ASSOC);
        $hashedPassword = $userRecord['password'];

        return password_verify($password, $hashedPassword);
    }

    public function registerUser(string $username, string $email, string $password): bool
    {
        $normalizedUsername = strtolower($username);
        $normalizedEmail = strtolower($email);

        $query = "INSERT INTO userdata (username, email, password, normalized_username) VALUES (:username, :email, :password, :normalized_username)";
        $statement = $this->databaseConnection->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->bindParam(':email', $normalizedEmail);
        $statement->bindParam(':password', $password);
        $statement->bindParam(':normalized_username', $normalizedUsername);

        return $statement->execute();
    }

    public function emailExists(string $email): bool
    {
        $normalizedEmail = strtolower($email);

        $query = "SELECT email FROM userdata WHERE email = :email";
        $statement = $this->databaseConnection->prepare($query);
        $statement->bindParam(':email', $normalizedEmail);
        $statement->execute();

        return $statement->rowCount() > 0;
    }

    public function usernameExists(string $username): bool
    {
        $normalizedUsername = strtolower($username);

        $query = "SELECT normalized_username FROM userdata WHERE normalized_username = :username";
        $statement = $this->databaseConnection->prepare($query);
        $statement->bindParam(':username', $normalizedUsername);
        $statement->execute();

        return $statement->rowCount() > 0;
    }

    public function getUserId(string $username): int
    {
        $normalizedUsername = strtolower($username);

        $query = "SELECT id FROM userdata WHERE normalized_username = :username";
        $statement = $this->databaseConnection->prepare($query);
        $statement->bindParam(':username', $normalizedUsername);
        $statement->execute();

        $userRecord = $statement->fetch(\PDO::FETCH_ASSOC);

        return $userRecord['id'];
    }

    public function userIdExists(int $userId): bool
    {
        $query = "SELECT id FROM userdata WHERE id = :id";
        $statement = $this->databaseConnection->prepare($query);
        $statement->bindParam(':id', $userId);
        $statement->execute();

        return $statement->rowCount() > 0;
    }

}
