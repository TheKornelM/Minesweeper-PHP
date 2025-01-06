<?php

namespace Managers;

use Repository\Interfaces\SaveRepositoryInterface;
use Repository\Interfaces\UserRepositoryInterface;
use DTOs\ShowSavesDto;
use Validators\Result;

class SaveManager
{
    private $saveRepository;
    private $userRepository;

    function __construct(SaveRepositoryInterface $saveRepository, UserRepositoryInterface $userRepository)
    {
        $this->saveRepository = $saveRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Show basic saves data in array of ShowSavesDto objects.
     *
     * @param int $userId Given user ID
     * @return array User saves (ShowSavesDto)
     */
    public function showSaves(int $userId): array
    {
        return $this->saveRepository->getSaveIdsWithNames($userId);
    }

    public function createSave(int $userId, string $saveName, $boardState, $elapsedTime) : Result
    {
        if (!$this->userRepository->userIdExists($userId))
        {
            return new Result(false, ["User id ${userId} not exists"]);
        }

        return $this->saveRepository->createSave($userId, $saveName, $boardState, $elapsedTime);
    }

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

    public function deleteUserSaves(int $userId): Result
    {
        if (!$this->userRepository->userIdExists($userId))
        {
            return new Result(false, ["User id ${userId} not exists"]);
        }

        return $this->saveRepository->deleteUserSaves($userId);
    }
}