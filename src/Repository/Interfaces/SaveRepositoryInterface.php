<?php

namespace Repository\Interfaces;

use Validators\Result;

interface SaveRepositoryInterface
{

    /**
     * Show basic saves data in array of ShowSavesDto objects.
     *
     * @param int $userId Given user ID
     * @return array User saves (ShowSavesDto)
     */
    public function getSaveIdsWithNames(int $userId): array;
    public function deleteSaveById(int $saveId): Result;
    public function getSaveData(int $saveId);

}