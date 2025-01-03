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

    /**
     * Verifies if the provided email and password match a user in the database.
     *
     * @param string $email    The email address of the user.
     * @param string $password The plaintext password entered by the user.
     * @return bool Returns true if the credentials are valid, otherwise false.
     */
    public function isValidCredentials(string $email, string $password): bool
    {
        $query = "SELECT password FROM userdata WHERE email = :email";
        $statement = $this->databaseConnection->prepare($query);
        $statement->bindParam(':email', $email);
        $statement->execute();

        if ($statement->rowCount() === 0) {
            return false;
        }

        $userRecord = $statement->fetch(\PDO::FETCH_ASSOC);
        $hashedPassword = $userRecord['password'];

        return password_verify($password, $hashedPassword);
    }

    /**
     * Registers a new user by inserting their details into the database.
     *
     * @param string $username The username chosen by the user.
     * @param string $email    The email address of the user.
     * @param string $password The hashed password of the user.
     * @return bool Returns true if the user is successfully registered, otherwise false.
     */
    public function registerUser(string $username, string $email, string $password): bool
    {
        $query = "INSERT INTO userdata (username, email, password) VALUES (:username, :email, :password)";
        $statement = $this->databaseConnection->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':password', $password);

        return $statement->execute();
    }

    /**
     * Checks if an email address already exists in the database.
     *
     * @param string $email The email address to check.
     * @return bool Returns true if the email exists, otherwise false.
     */
    public function emailExists(string $email): bool
    {
        $query = "SELECT email FROM userdata WHERE email = :email";
        $statement = $this->databaseConnection->prepare($query);
        $statement->bindParam(':email', $email);
        $statement->execute();

        return $statement->rowCount() > 0;
    }

    /**
     * Checks if a username already exists in the database.
     *
     * @param string $username The username to check.
     * @return bool Returns true if the username exists, otherwise false.
     */
    public function usernameExists(string $username): bool
    {
        $query = "SELECT username FROM userdata WHERE username = :username";
        $statement = $this->databaseConnection->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->execute();

        return $statement->rowCount() > 0;
    }
}
