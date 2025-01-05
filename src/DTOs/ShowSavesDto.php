<?php

namespace DTOs;

class ShowSavesDto
{
    public $id;
    public $name;

    function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}