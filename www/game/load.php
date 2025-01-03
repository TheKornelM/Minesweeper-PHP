<?php

include '../../db_connection.php';
include "../../src/Managers/UserManager.php";
include "../../src/Repository/Interfaces/UserRepositoryInterface.php";
include "../../src/Repository/PostgreRepositories/UserRepository.php";

use Repository\PostgreRepositories\UserRepository;
use Managers\UserManager;

session_start();

if (!isset($_SESSION["username"])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
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
            $boardFilePath = "boards/" . $game["board_file_name"];
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