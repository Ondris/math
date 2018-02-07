<?php

use Nette\Database\Table\IRow;

abstract class BaseEntity
{
    /**
     * @var IRow
     */
    protected $row;

    public function __construct(IRow $row)
    {
        $this->row = $row;
    }
}