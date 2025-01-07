<?php

namespace Managers;

use Repository\Interfaces\SaveRepositoryInterface;
use Repository\Interfaces\UserRepositoryInterface;
use DTOs\ShowSavesDto;
use Validators\Result;

/**
 * Class SaveManager
 * Manages save-related operations, including creating, retrieving, and deleting saves.
 */
class SaveManager
{
    private $saveRepository;
    private $userRepository;

    /**
     * SaveManager constructor.
     *
     * @param SaveRepositoryInterface $saveRepository Repository for managing saves.
     * @param UserRepositoryInterface $userRepository Repository for managing user data.
     */
    function __construct(SaveRepositoryInterface $saveRepository, UserRepositoryInterface $userRepository)
    {
        $this->saveRepository = $saveRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Retrieves board data for a given user and game.
     *
     * @param int $userId User ID.
     * @param int $gameId Game ID.
     * @return Result|array Board data or a Result object indicating failure.
     */
    public function getBoardData(int $userId, int $gameId)
    {
        // User ID check
        if (!$this->userRepository->userIdExists($userId))
        {
            return new Result(false, ["User id ${userId} not exists"]);
        }

        return $this->saveRepository->getBoardData($userId, $gameId);
    }

    /**
     * Retrieves an array of ShowSavesDto objects representing the user's saves.
     *
     * @param int $userId User ID.
     * @return ShowSavesDto[] Array of user saves.
     */
    public function showSaves(int $userId): array
    {
        return $this->saveRepository->getSaveIdsWithNames($userId);
    }

    /**
     * Creates a new save for a given user.
     *
     * @param int $userId User ID.
     * @param string $saveName Name of the save.
     * @param mixed $boardState Current state of the game board.
     * @param mixed $elapsedTime Elapsed time for the save.
     * @return Result Result object indicating success or failure.
     */
    public function createSave(int $userId, string $saveName, $boardState, $elapsedTime) : Result
    {
        if (!$this->userRepository->userIdExists($userId))
        {
            return new Result(false, ["User id ${userId} not exists"]);
        }

        return $this->saveRepository->createSave($userId, $saveName, $boardState, $elapsedTime);
    }

    /**
     * Deletes a specific save for a given user.
     *
     * @param int $userId User ID.
     * @param int $saveId Save ID to delete.
     * @return Result Result object indicating success or failure.
     */
    public function deleteSave(int $userId, int $saveId): Result
    {
        if (!$this->userRepository->userIdExists($userId))
        {
            return new Result(false, ["User id ${userId} not exists"]);
        }

        if(intval($this->saveRepository->getSaveData($saveId)["user_id"]) !== $userId)
        {
            return new Result(false, ["Save not found"]);
        }

        return $this->saveRepository->deleteSaveById($saveId);
    }

    /**
     * Deletes all saves for a given user.
     *
     * @param int $userId User ID.
     * @return Result Result object indicating success or failure.
     */
    public function deleteUserSaves(int $userId): Result
    {
        if (!$this->userRepository->userIdExists($userId))
        {
            return new Result(false, ["User id ${userId} not exists"]);
        }

        return $this->saveRepository->deleteUserSaves($userId);
    }
}
