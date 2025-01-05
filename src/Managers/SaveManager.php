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

    public function showSaves(int $userId): array
    {
        return $this->saveRepository->getSaveIdsWithNames($userId);
    }
}