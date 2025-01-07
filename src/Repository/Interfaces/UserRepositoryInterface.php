<?php

namespace Repository\Interfaces;

/**
 * UserRepositoryInterface: Interface for managing user-related operations in a repository.
 */
interface UserRepositoryInterface
{
    /**
     * Registers a new user.
     *
     * @param string $username The username of the user.
     * @param string $email The email address of the user.
     * @param string $password The hashed password of the user.
     * @return bool Returns true if the registration is successful, otherwise false.
     */
    public function registerUser(string $username, string $email, string $password): bool;

    /**
     * Checks if an email address already exists in the repository.
     *
     * @param string $email The email address to check.
     * @return bool Returns true if the email exists, otherwise false.
     */
    public function emailExists(string $email): bool;

    /**
     * Checks if a username already exists in the repository.
     *
     * @param string $username The username to check.
     * @return bool Returns true if the username exists, otherwise false.
     */
    public function usernameExists(string $username): bool;

    /**
     * Validates user credentials against the stored data in the repository.
     *
     * @param string $username The username provided by the user.
     * @param string $password The plaintext password provided by the user.
     * @return bool Returns true if the credentials are valid, otherwise false.
     */
    public function isValidCredentials(string $username, string $password): bool;

    /**
     * Retrieves the ID of a user by their username.
     *
     * @param string $username The username of the user.
     * @return int Returns the user ID.
     */
    public function getUserId(string $username): int;

    /**
     * Checks if a user ID exists in the repository.
     *
     * @param int $userId The user ID to check.
     * @return bool Returns true if the user ID exists, otherwise false.
     */
    public function userIdExists(int $userId): bool;
}
