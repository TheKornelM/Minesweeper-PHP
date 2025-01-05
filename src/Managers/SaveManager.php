<?php

namespace Managers;

use Repository\Interfaces\SaveRepositoryInterface;
use DTOs\ShowSavesDto;
use Validators\Result;

class SaveManager
{
    private $saveRepository;

    function __construct(SaveRepositoryInterface $saveRepository)
    {
        $this->saveRepository = $saveRepository;
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

    public function deleteSave(int $userId, int $saveId): Result
    {
        if(intval($this->saveRepository->getSaveData($saveId)["user_id"]) !== $userId)
        {
            return new Result(false, ["Save not found"]);
        }

        return $this->saveRepository->deleteSaveById($saveId);
    }
}