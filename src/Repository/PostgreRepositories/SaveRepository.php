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


}