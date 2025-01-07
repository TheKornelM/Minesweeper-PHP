<?php

namespace Repository\Interfaces;

use Validators\Result;

interface SaveRepositoryInterface
{
    /**
     * Retrieves a list of save IDs and their corresponding names for a user.
     *
     * @param int $userId The ID of the user whose saves should be retrieved.
     * @return array An array of `ShowSavesDto` objects representing the user's saves.
     */
    public function getSaveIdsWithNames(int $userId): array;

    /**
     * Deletes a specific save by its ID.
     *
     * @param int $saveId The ID of the save to delete.
     * @return Result The result of the delete operation, including success status and any errors.
     */
    public function deleteSaveById(int $saveId): Result;

    /**
     * Retrieves all save data for a given save ID.
     *
     * @param int $saveId The ID of the save to retrieve.
     * @return mixed The save data, or null if the save is not found.
     */
    public function getSaveData(int $saveId);

    /**
     * Deletes all saves associated with a specific user.
     *
     * @param int $userId The ID of the user whose saves should be deleted.
     * @return Result The result of the delete operation, including success status and any errors.
     */
    public function deleteUserSaves(int $userId): Result;

    /**
     * Creates a new save for a user, including board state and elapsed time information.
     *
     * @param int $userId The ID of the user creating the save.
     * @param string $saveName The name of the save.
     * @param mixed $boardState The board state data to save (e.g., JSON-encoded string).
     * @param array $elapsedTime An associative array containing elapsed time details
     *                           (e.g., ['hour' => int, 'minute' => int, 'second' => int, 'count' => int]).
     * @return Result The result of the create operation, including success status and any errors.
     */
    public function createSave(int $userId, string $saveName, $boardState, $elapsedTime): Result;
}
