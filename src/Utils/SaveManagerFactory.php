<?php

namespace Utils;

use Managers\SaveManager;
use Repository\PostgreRepositories\SaveRepository;
use Repository\PostgreRepositories\UserRepository;

class SaveManagerFactory
{
    public static function create(\PDO $conn): SaveManager
    {
        $saveRepo = new SaveRepository($conn);
        $userRepo = new UserRepository($conn);
        return new SaveManager($saveRepo, $userRepo);
    }
}