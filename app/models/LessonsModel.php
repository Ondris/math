<?php

namespace Model;

final class LessonsModel extends BaseModel
{
    const TABLE = "lessons";
    
    public function getLessons($visible) 
    {
	$rows = $this->database->table(self::TABLE);
	
	if ($visible) {
	    $rows->where("visible", $visible);
	}
	
	$lessons = [];
	foreach ($rows as $row) {
	    $lessons[] = new Lesson($row);
	}
	
	return $lessons;
    }
    
    public function getLesson($id)
    {
	$row = $this->database->table(self::TABLE)
		->get($id);
	
	return $row ? new Lesson($row) : NULL;
    }
    
    public function updateLesson($values, $lessonId)
    {
	$this->database->table(self::TABLE)
		->where("id", $lessonId)
		->update($values);
    }

    public function saveLesson($values)
    {
	$values["order"] = $this->database->table(self::TABLE)->max("order") + 1;
	
	$this->database->table(self::TABLE)
		->insert($values);
    }
    
}
