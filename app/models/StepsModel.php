<?php

namespace Model;

final class StepsModel extends BaseModel
{
    const TABLE = "steps";
    
    public function getStep($id)
    {
	$row = $this->database->table(self::TABLE)
		->get($id);
	
	return $row ? new Step($row) : NULL;
    }
    
    public function updateStep($values, $stepId)
    {
	$this->database->table(self::TABLE)
		->where("id", $stepId)
		->update($values);
    }

    public function saveStep($values)
    {
	$values["order"] = $this->database->table(self::TABLE)->where("task_id", $values["task_id"])->max("order") + 1;
	
	$this->database->table(self::TABLE)
		->insert($values);
    }
    
}
