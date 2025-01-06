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

use Repository\PostgreRepositories\UserRepository;
use Managers\UserManager;
use Repository\PostgreRepositories\SaveRepository;
use Managers\SaveManager;
use Utils\SetHeader;

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

    if (!$saveName || !$elapsedTime || !$boardState) {
        http_response_code(400);
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }

    // Example: Access specific elapsed time values
    $hour = $elapsedTime['hour'] ?? 0;
    $minute = $elapsedTime['minute'] ?? 0;
    $second = $elapsedTime['second'] ?? 0;

    // Validate input
    if (!$userId || !$saveName || !$elapsedTime || !$boardState) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
        exit;
    }

    // Generate a unique filename for the board JSON
    $fileName = uniqid('board_', true) . '.json';
    $filePath = __DIR__ . '/boards/' . $fileName;

    // Save the board state as a JSON file
    if (file_put_contents($filePath, $boardState) === false) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save board state.']);
        exit;
    }

    // Insert elapsed time
    $sql = 'INSERT INTO elapsed_times (hour, minute, second, count) VALUES (?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);
    $elapsedTimeData = [$elapsedTime['hour'], $elapsedTime['minute'], $elapsedTime['second'], $elapsedTime['count']];
    $stmt->execute($elapsedTimeData);
    $elapsedTimeId = $conn->lastInsertId();

    // Insert game metadata
    $sql = 'INSERT INTO games (user_id, save_name, elapsed_time_id, board_file_name) VALUES (?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$userId, $saveName, $elapsedTimeId, $fileName])) {
        echo json_encode(['status' => 'success', 'message' => 'Game saved successfully.']);
    } else {
        // Rollback if DB operation fails
        $query = "DELETE FROM elapsed_times WHERE id = :id";
        $statement = $conn->prepare($query);
        $statement->bindParam(':id', $elapsedTimeId, PDO::PARAM_INT);
        $statement->execute();

        // Safely delete the file
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        echo json_encode(['status' => 'error', 'message' => 'Failed to save game metadata.']);
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(!isAuthenticated()){
        http_response_code(404);
        return;
    }

    $userId = getUserId($conn);

    $saveRepository = new SaveRepository($conn);
    $saveManager = new SaveManager($saveRepository);

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

    $saveRepository = new SaveRepository($conn);
    $saveManager = new SaveManager($saveRepository);

    // Delete all saves if saveId equals to "*"
    if($saveId == "*"){
        echo json_encode($saveManager->deleteUserSaves($userId));
        return;
    }

    echo json_encode($saveManager->deleteSave($userId, $saveId));
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