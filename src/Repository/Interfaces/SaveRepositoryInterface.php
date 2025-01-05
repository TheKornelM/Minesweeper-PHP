<?php

namespace Repository\Interfaces;

interface SaveRepositoryInterface
{
    public function getSaveIdsWithNames(int $userId): array;
}