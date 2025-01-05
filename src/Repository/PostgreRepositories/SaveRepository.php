<?php

namespace Repository\PostgreRepositories;

use Repository\Interfaces\SaveRepositoryInterface;
use DTOs\ShowSavesDto;

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
}