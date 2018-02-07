<?php

namespace Model;

final class Lesson extends \BaseEntity
{
    public function getId()
    {
        return $this->row->id;
    }

    public function getName()
    {
        return $this->row->name;
    }
    
    public function getDescription()
    {
	return $this->row->description;
    }
    
    public function getVisible()
    {
	return $this->row->visible;
    }


    public function getTasks()
    {
	$rows = $this->row->related(TasksModel::TABLE, "lesson_id");
	
	$tasks = [];
	foreach ($rows as $row) {
	    $tasks[] = new Task($row);
	}
	
	return $tasks;
    }
}
