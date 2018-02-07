<?php

namespace Model;

final class UsersStepsModel extends BaseModel
{
    const TABLE = "users_steps";
    
    public function save($userId, $stepId)
    {
	$hasBeenModified = $this->database->table(self::TABLE)
		->where("users_id", $userId)
		->where("steps_id", $stepId)
		->update(["date" => new \Nette\Utils\DateTime()]);
		
	if (!$hasBeenModified) {
	    $this->database->table(self::TABLE)
		->insert(["users_id" => $userId, "steps_id" => $stepId, "date" => new \Nette\Utils\DateTime()]);
	}
    }
}
