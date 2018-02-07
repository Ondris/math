<?php

namespace Model;

abstract class BaseModel
{
    /** @var \Nette\Database\Context */
    protected $database;

    public function __construct(\Nette\Database\Context $database) {
        $this->database = $database;
    }
    
}