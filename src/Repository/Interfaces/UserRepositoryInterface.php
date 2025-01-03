<?php namespace Repository\Interfaces;

interface UserRepositoryInterface
{
    public function registerUser(string $username, string $email, string $password): bool;
    public function emailExists(string $email): bool;
    public function usernameExists(string $username): bool;
    public function isValidCredentials(string $username, string $password): bool;

}