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

// Check if the necessary data is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();

    if (!isset($_SESSION['username'])){
        http_response_code(404);
    }

    $userRepo = new UserRepository($conn);
    $userManager = new UserManager($userRepo);

    $userId = $userManager->getUserId($_SESSION['username']);
    // Get raw POST data
    $rawInput = file_get_contents("php://input");

    // Decode JSON input
    $data = json_decode($rawInput, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid JSON input"]);
        exit;
    }

    // Access JSON properties
    $saveName = $data['save_name'] ?? null;
    $elapsedTime = $data['elapsed_time'] ?? null;
    $boardState = $data['board'] ?? null;

    if (!$userId || !$saveName || !$elapsedTime || !$boardState) {
        http_response_code(400);
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }

    $saveManager = SaveManagerFactory::create($conn);
    SetHeader::ToJson();
    echo json_encode($saveManager->createSave($userId, $saveName, $boardState, $elapsedTime));
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(!isAuthenticated()){
        http_response_code(404);
        return;
    }

    $userId = getUserId($conn);
    $saveManager = SaveManagerFactory::create($conn);
    SetHeader::ToJson();
    echo json_encode($saveManager->showSaves($userId));
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!isAuthenticated()) {
        http_response_code(404);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true, JSON_NUMERIC_CHECK);

    if (!isset($data['saveId'])) {
        http_response_code(400);
        echo json_encode(['error' => 'saveId is required']);
        return;
    }

    $saveId = $data['saveId'];
    $userId = getUserId($conn);

    $saveManager = SaveManagerFactory::create($conn);

    SetHeader::ToJson();

    // Delete all saves if saveId equals to "*"
    if($saveId == "*"){
        echo json_encode($saveManager->deleteUserSaves($userId));
        return;
    }

    if(!is_int($saveId)){
        http_response_code(400);
        echo json_encode(['error' => 'Save id must be an integer']);
        return;
    }

    echo json_encode($saveManager->deleteSave($userId, intval($saveId)));
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