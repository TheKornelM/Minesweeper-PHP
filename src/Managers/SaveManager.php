<?php

namespace Managers;

use Repository\Interfaces\SaveRepositoryInterface;
use DTOs\ShowSavesDto;

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
}