<?php

namespace Model;

final class Task extends \BaseEntity
{
    public function getId()
    {
        return $this->row->id;
    }
    
    public function getDescription()
    {
	return $this->row->description;
    }
    
    public function getVisible()
    {
	return $this->row->visible;
    }
    
    public function getLessonId()
    {
	return $this->row->lesson_id;
    }


    public function getSteps($numberOfSteps = 0)
    {
	$steps = $this->getAllSteps();
	
	if ($numberOfSteps) {
	    return array_slice($steps, 0, $numberOfSteps);
	} else {
	    return $steps;
	}
    }
    
    public function getStep($index) {
	$steps = $this->getAllSteps();
	
	return isset($steps[$index - 1]) ? $steps[$index - 1] : NULL;
    }
    
    private function getAllSteps() {
	$rows = $this->row->related(StepsModel::TABLE, "task_id")->order("order");

	$steps = [];
	foreach ($rows as $row) {
	    $steps[] = new Step($row);
	}
	
	return $steps;
    }
    
    public function countSteps()
    {
	return $this->row->related(StepsModel::TABLE, "task_id")->count();
    }
}
