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

    $userRepo = new UserRepository($conn);
    $userManager = new UserManager($userRepo);

    $userId = $userManager->getUserId($_SESSION['username']);
    $gameId = $_GET["id"] ?? null;

    if (!$gameId) {
        http_response_code(400);
        echo json_encode(["error" => "Game ID is required"]);
        exit;
    }

    $gameData = loadGameFromDatabase($userId, $gameId);

    if (!$gameData) {
        http_response_code(404);
        echo json_encode(["error" => "Game not found"]);
        exit;
    }

// Remove unnecessary fields or transform if needed
    $gameData["elapsed_time"] = [
        "hour" => $gameData["hour"],
        "minute" => $gameData["minute"],
        "second" => $gameData["second"],
        "count" => $gameData["count"],
    ];
    unset($gameData["hour"], $gameData["minute"], $gameData["second"], $gameData["count"]);

    echo json_encode($gameData);

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

function loadGameFromDatabase($userId, $gameId) {
    global $conn;

    try {
        // Query to fetch the saved game
        $query = "SELECT g.id, g.save_name, g.board_file_name, g.saved_at, et.hour, et.minute, et.second, et.count 
                  FROM games g 
                  JOIN elapsed_times et ON g.elapsed_time_id = et.id
                  WHERE g.user_id = :userId AND g.id = :gameId";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        $stmt->bindParam(":gameId", $gameId, PDO::PARAM_INT);

        $stmt->execute();

        // Fetch the saved game metadata
        $game = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($game) {
            // Read the board JSON file
            $baseDir = dirname(__DIR__, 2);
            $boardFilePath = $baseDir . "/data/boards/" . $game["board_file_name"];

            if (file_exists($boardFilePath)) {
                $game["board_data"] = json_decode(file_get_contents($boardFilePath), true);
            } else {
                $game["board_data"] = null; // Handle missing file gracefully
            }
        }

        return $game ? $game : null;

    } catch (PDOException $e) {
        // Handle errors (optional: log errors)
        error_log("Database error: " . $e->getMessage());
        return null;
    }
}