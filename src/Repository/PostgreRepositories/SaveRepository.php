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
     * Constructor to initialize the UserRepository with a PDO connection.
     *
     * @param \PDO $databaseConnection The PDO database connection instance.
     */
    public function __construct(\PDO $databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }

    public function getSaveIdsWithNames(int $userId): array
    {
        $query = "SELECT id, save_name FROM games WHERE user_id = :userId";
        $statement = $this->databaseConnection->prepare($query);
        $statement->bindParam(':userId', $userId);
        $statement->execute();

        // Fetch results as associative arrays
        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

        // Map results to ShowSavesDto objects
        $saves = [];
        foreach ($results as $row) {
            $saves[] = new ShowSavesDto((int)$row['id'], $row['save_name']);
        }

        return $saves;
    }

    public function getSaveData(int $saveId)
    {
        $query = "SELECT * FROM games WHERE id = :id";
        $statement = $this->databaseConnection->prepare($query);
        $statement->bindParam(':id', $saveId);
        $statement->execute();

        // Fetch results as associative arrays
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        return $result;
    }

    public function createSave(int $userId, string $saveName, $boardState, $elapsedTime) : Result
    {
        // Generate a unique filename for the board JSON
        $fileName = uniqid('board_', true) . '.json';
        $baseDir = dirname(__DIR__, 3);
        $filePath = $baseDir . '/data/boards/' . $fileName;

        // Save the board state as a JSON file
        if (file_put_contents($filePath, $boardState) === false)
        {
            return new Result(false, ['Failed to save board state.']);
        }

        // Insert elapsed time
        $sql = 'INSERT INTO elapsed_times (hour, minute, second, count) VALUES (?, ?, ?, ?)';
        $stmt = $this->databaseConnection->prepare($sql);
        $elapsedTimeData = [$elapsedTime['hour'], $elapsedTime['minute'], $elapsedTime['second'], $elapsedTime['count']];
        $stmt->execute($elapsedTimeData);
        $elapsedTimeId = $this->databaseConnection->lastInsertId();

        // Insert game metadata
        $sql = 'INSERT INTO games (user_id, save_name, elapsed_time_id, board_file_name) VALUES (?, ?, ?, ?)';
        $stmt = $this->databaseConnection->prepare($sql);

        if ($stmt->execute([$userId, $saveName, $elapsedTimeId, $fileName]))
        {
            return new Result(true);
        }
        else
        {
            // Rollback if DB operation fails
            $query = "DELETE FROM elapsed_times WHERE id = :id";
            $statement = $this->databaseConnection->prepare($query);
            $statement->bindParam(':id', $elapsedTimeId, PDO::PARAM_INT);
            $statement->execute();

            // Safely delete the file
            if (file_exists($filePath))
            {
                unlink($filePath);
            }

            return new Result(false, ['Failed to save game metadata.']);
        }
    }

    /**
     * Deletes a save by its ID and returns the result.
     *
     * @param int $saveId The ID of the save to delete.
     * @return Result The result of the deletion process.
     */
    public function deleteSaveById(int $saveId): Result {
        try {
            // Begin transaction
            $this->databaseConnection->beginTransaction();

            // Delete from elapsed_times first
            $query = "DELETE FROM elapsed_times WHERE id = :id";
            $statement = $this->databaseConnection->prepare($query);
            $statement->bindParam(':id', $elapsedTimeId, \PDO::PARAM_INT);
            $statement->execute();

            // Delete from games
            $query = "DELETE FROM games WHERE id = :id";
            $statement = $this->databaseConnection->prepare($query);
            $statement->bindParam(':id', $saveId, \PDO::PARAM_INT);
            $statement->execute();

            // Commit transaction
            $this->databaseConnection->commit();
            return new Result(true);
        } catch (\PDOException $e) {
            // Rollback transaction in case of error
            $this->databaseConnection->rollBack();
            return new Result(false, ["Database error: " . $e->getMessage()]);
        }
    }

    public function deleteUserSaves(int $userId): Result
    {
        try {
            // Begin transaction
            $this->databaseConnection->beginTransaction();

            // Delete from elapsed_times using a JOIN with games
            $query = "
            DELETE FROM elapsed_times
            WHERE id IN (
                SELECT elapsed_time_id
                FROM games
                WHERE user_id = :userId
            )
        ";

            $statement = $this->databaseConnection->prepare($query);
            $statement->bindParam(':userId', $userId, \PDO::PARAM_INT);
            $statement->execute();

            // Delete from games
            $query = "DELETE FROM games WHERE user_id = :userId";
            $statement = $this->databaseConnection->prepare($query);
            $statement->bindParam(':userId', $userId, \PDO::PARAM_INT);
            $statement->execute();

            // Commit transaction
            $this->databaseConnection->commit();
            return new Result(true);
        } catch (\PDOException $e) {
            // Rollback transaction in case of error
            $this->databaseConnection->rollBack();
            return new Result(false, ["Database error: " . $e->getMessage()]);
        }
    }


}