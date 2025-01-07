<?php

include "../../src/DTOs/ShowSavesDto.php";
include "../../src/Validators/Result.php";
include  '../../db_connection.php';
include "../../src/Managers/UserManager.php";
include "../../src/Repository/Interfaces/UserRepositoryInterface.php";
include "../../src/Repository/PostgreRepositories/UserRepository.php";
include "../../src/Repository/Interfaces/SaveRepositoryInterface.php";
include "../../src/Repository/PostgreRepositories/SaveRepository.php";
include "../../src/Managers/SaveManager.php";
include "../../src/Utils/SetHeader.php";
include "../../src/Utils/SaveManagerFactory.php";

use Repository\PostgreRepositories\UserRepository;
use Managers\UserManager;
use Utils\SetHeader;
use Utils\SaveManagerFactory;

$saveManager = SaveManagerFactory::create($conn);

// Check if the necessary data is provided
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(!isAuthenticated()){
        http_response_code(404);
        return;
    }

    $userId = getUserId($conn);

    SetHeader::ToJson();
    echo json_encode($saveManager->showSaves($userId));
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!isAuthenticated()) {
        http_response_code(404);
        return;
    }
    $userId = getUserId($conn);

    SetHeader::ToJson();
    echo json_encode($saveManager->deleteUserSaves($userId));
}

function isAuthenticated(): bool
{
    session_start();
    return isset($_SESSION['username']);
}

function getUserId(\PDO $conn): int
{
    $userRepo = new UserRepository($conn);
    $userManager = new UserManager($userRepo);

    return $userManager->getUserId($_SESSION['username']);
}