<?php namespace Managers;

use Repository\Interfaces\UserRepositoryInterface;

class UserManager
{
    private $userRepository;

    function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(string $username, string $email, string $password)
    {
        return $this->userRepository->registerUser($username, $email, password_hash($password, PASSWORD_BCRYPT));
    }

    public function emailExists(string $email): bool
    {
        return $this->userRepository->emailExists($email);
    }

    public function usernameExists(string $username): bool
    {
        return $this->userRepository->usernameExists($username);
    }

    public function isValidCredentials($email, $password): bool{
        return $this->userRepository->isValidCredentials($email, $password);
    }
}
