<?php

namespace Model;

final class TasksModel extends BaseModel
{
    const TABLE = "tasks";
    
    public function getTask($id)
    {
	$row = $this->database->table(self::TABLE)
		->get($id);
	
	return $row ? new Task($row) : NULL;
    }
    
    public function updateTask($values, $taskId)
    {
	$this->database->table(self::TABLE)
		->where("id", $taskId)
		->update($values);
    }

    public function saveTask($values)
    {
	$values["order"] = $this->database->table(self::TABLE)->where("lesson_id", $values["lesson_id"])->max("order") + 1;
	
	$this->database->table(self::TABLE)
		->insert($values);
    }
}
