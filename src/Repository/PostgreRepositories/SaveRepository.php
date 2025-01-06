<?php

namespace Repository\PostgreRepositories;

use Repository\Interfaces\SaveRepositoryInterface;
use DTOs\ShowSavesDto;
use Validators\Result;

class SaveRepository implements SaveRepositoryInterface
{
    /**
     * @var \PDO $databaseConnection The PDO database connection instance.
     */
    private $databaseConnection;

    /**
     * Constructor to initialize the SaveRepository with a PDO connection.
     *
     * @param \PDO $databaseConnection The PDO database connection instance.
     */
    public function __construct(\PDO $databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }

    /**
     * Retrieves save IDs and names for a given user.
     *
     * @param int $userId The ID of the user.
     * @return ShowSavesDto[] An array of ShowSavesDto objects.
     */
    public function getSaveIdsWithNames(int $userId): array
    {
        $query = "SELECT id, save_name FROM games WHERE user_id = :userId";
        $statement = $this->databaseConnection->prepare($query);
        $statement->bindParam(':userId', $userId);
        $statement->execute();

        // Fetch results and map them to DTO objects
        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $saves = [];
        foreach ($results as $row) {
            $saves[] = new ShowSavesDto((int)$row['id'], $row['save_name']);
        }

        return $saves;
    }

    /**
     * Retrieves all save data for a specific save ID.
     *
     * @param int $saveId The ID of the save.
     * @return array|null The save data or null if not found.
     */
    public function getSaveData(int $saveId)
    {
        $query = "SELECT * FROM games WHERE id = :id";
        $statement = $this->databaseConnection->prepare($query);
        $statement->bindParam(':id', $saveId);
        $statement->execute();

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Creates a new save for a user, including board state and elapsed time.
     *
     * @param int $userId The ID of the user.
     * @param string $saveName The name of the save.
     * @param string $boardState The board state in JSON format.
     * @param array $elapsedTime An associative array containing elapsed time details.
     * @return Result The result of the save operation.
     */
    public function createSave(int $userId, string $saveName, $boardState, $elapsedTime): Result
    {
        $fileName = uniqid('board_', true) . '.json';
        $baseDir = dirname(__DIR__, 3);
        $filePath = $baseDir . '/data/boards/' . $fileName;

        if (file_put_contents($filePath, $boardState) === false) {
            return new Result(false, ['Failed to save board state.']);
        }

        $sql = 'INSERT INTO elapsed_times (hour, minute, second, count) VALUES (?, ?, ?, ?)';
        $stmt = $this->databaseConnection->prepare($sql);
        $stmt->execute([$elapsedTime['hour'], $elapsedTime['minute'], $elapsedTime['second'], $elapsedTime['count']]);
        $elapsedTimeId = $this->databaseConnection->lastInsertId();

        $sql = 'INSERT INTO games (user_id, save_name, elapsed_time_id, board_file_name) VALUES (?, ?, ?, ?)';
        $stmt = $this->databaseConnection->prepare($sql);

        if ($stmt->execute([$userId, $saveName, $elapsedTimeId, $fileName])) {
            return new Result(true);
        } else {
            $this->deleteFile($filePath);

            $query = "DELETE FROM elapsed_times WHERE id = :id";
            $statement = $this->databaseConnection->prepare($query);
            $statement->bindParam(':id', $elapsedTimeId, \PDO::PARAM_INT);
            $statement->execute();

            return new Result(false, ['Failed to save game metadata.']);
        }
    }

    /**
     * Deletes a save by its ID, including associated elapsed time and JSON file.
     *
     * @param int $saveId The ID of the save to delete.
     * @return Result The result of the deletion process.
     */
    public function deleteSaveById(int $saveId): Result
    {
        try {
            $this->databaseConnection->beginTransaction();

            $gameData = $this->getSaveData($saveId);
            if (!$gameData) {
                return new Result(false, ['Save not found.']);
            }

            $elapsedTimeId = $gameData['elapsed_time_id'];
            $fileName = $gameData['board_file_name'];
            $filePath = dirname(__DIR__, 3) . '/data/boards/' . $fileName;

            $query = "DELETE FROM elapsed_times WHERE id = :id";
            $statement = $this->databaseConnection->prepare($query);
            $statement->bindParam(':id', $elapsedTimeId, \PDO::PARAM_INT);
            $statement->execute();

            $query = "DELETE FROM games WHERE id = :id";
            $statement = $this->databaseConnection->prepare($query);
            $statement->bindParam(':id', $saveId, \PDO::PARAM_INT);
            $statement->execute();

            $this->databaseConnection->commit();
            $this->deleteFile($filePath);

            return new Result(true);
        } catch (\PDOException $e) {
            $this->databaseConnection->rollBack();
            return new Result(false, ["Database error: " . $e->getMessage()]);
        }
    }

    /**
     * Deletes all saves for a user, including associated elapsed times and JSON files.
     *
     * @param int $userId The ID of the user whose saves are to be deleted.
     * @return Result The result of the deletion process.
     */
    public function deleteUserSaves(int $userId): Result
    {
        try {
            $this->databaseConnection->beginTransaction();

            $query = "SELECT id, board_file_name FROM games WHERE user_id = :userId";
            $statement = $this->databaseConnection->prepare($query);
            $statement->bindParam(':userId', $userId, \PDO::PARAM_INT);
            $statement->execute();
            $games = $statement->fetchAll(\PDO::FETCH_ASSOC);

            $query = "
                DELETE FROM elapsed_times
                WHERE id IN (
                    SELECT elapsed_time_id FROM games WHERE user_id = :userId
                )
            ";

            $statement = $this->databaseConnection->prepare($query);
            $statement->bindParam(':userId', $userId, \PDO::PARAM_INT);
            $statement->execute();

            $query = "DELETE FROM games WHERE user_id = :userId";
            $statement = $this->databaseConnection->prepare($query);
            $statement->bindParam(':userId', $userId, \PDO::PARAM_INT);
            $statement->execute();

            $this->databaseConnection->commit();

            foreach ($games as $game) {
                $filePath = dirname(__DIR__, 3) . '/data/boards/' . $game['board_file_name'];
                $this->deleteFile($filePath);
            }

            return new Result(true);
        } catch (\PDOException $e) {
            $this->databaseConnection->rollBack();
            return new Result(false, ["Database error: " . $e->getMessage()]);
        }
    }

    /**
     * Safely deletes a file from the filesystem.
     *
     * @param string $filePath The path to the file to delete.
     */
    private function deleteFile(string $filePath)
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
