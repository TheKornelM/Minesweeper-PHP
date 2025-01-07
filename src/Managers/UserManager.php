<?php namespace Managers;

use Repository\Interfaces\UserRepositoryInterface;

class UserManager
{
    /**
     * @var UserRepositoryInterface The repository instance for user-related database operations.
     */
    private $userRepository;

    /**
     * Constructor to initialize the UserManager with a UserRepository.
     *
     * @param UserRepositoryInterface $userRepository The user repository implementation.
     */
    function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Registers a new user by passing the provided details to the repository.
     *
     * @param string $username The username chosen by the user.
     * @param string $email The user's email address.
     * @param string $password The plaintext password to be hashed and stored.
     * @return bool Returns true if the user is successfully registered, otherwise false.
     */
    public function registerUser(string $username, string $email, string $password)
    {
        return $this->userRepository->registerUser($username, $email, password_hash($password, PASSWORD_BCRYPT));
    }

    /**
     * Checks if an email address already exists in the repository.
     *
     * @param string $email The email address to check.
     * @return bool Returns true if the email exists, otherwise false.
     */
    public function emailExists(string $email): bool
    {
        return $this->userRepository->emailExists($email);
    }

    /**
     * Checks if a username already exists in the repository.
     *
     * @param string $username The username to check.
     * @return bool Returns true if the username exists, otherwise false.
     */
    public function usernameExists(string $username): bool
    {
        return $this->userRepository->usernameExists($username);
    }

    /**
     * Validates user credentials by checking the username and password.
     *
     * @param string $username The username entered by the user.
     * @param string $password The plaintext password entered by the user.
     * @return bool Returns true if the credentials are valid, otherwise false.
     */
    public function isValidCredentials($username, $password): bool
    {
        return $this->userRepository->isValidCredentials($username, $password);
    }

    /**
     * Retrieves the user ID for a given username.
     *
     * @param string $username The username for which to retrieve the ID.
     * @return int The user ID associated with the given username.
     */
    public function getUserId(string $username): int
    {
        return $this->userRepository->getUserId($username);
    }
}
