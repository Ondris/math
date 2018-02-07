<?php

namespace Model;

final class User extends \BaseEntity
{
    const VISIBLE = 1;
    const INVISIBLE = 0;

    public function getId()
    {
        return $this->row->id;
    }

    public function getName()
    {
        return $this->row->name;
    }
    
    public function getPassword()
    {
	return $this->row->password;
    }
    
    public function getRole()
    {
	return $this->row->role;
    }


    public function getCode()
    {
	return $this->row->code;
    }

    public function getVisible()
    {
	return $this->row->visible;
    }
    
    public function getLernedTasks() {
	$completeSteps = [];
	$lessons = [];	
	foreach ($this->row->related(UsersStepsModel::TABLE, "users_id")->order("steps_id") as $row) {
	    $completeSteps[$row->steps_id] = $row->steps_id;
	    $lessons[$row->step->task->lesson->id] = new Lesson($row->step->task->lesson);
	}
	
	return $this->getFormatedLessons($lessons, $completeSteps);
    }
    
    private function getFormatedLessons(Array $lessons, Array $completeSteps) 
    {
	$formatedLessons = [];
	foreach ($lessons as $lesson) {
	    $formatedLessons[$lesson->getId()]["tasks"] = $this->getCompleteTasks($lesson, $completeSteps);
	    $formatedLessons[$lesson->getId()]["lesson"] = $lesson;
	}
	
	return $formatedLessons;
    }
    
    private function getCompleteTasks(Lesson $lesson, Array $completeSteps)
    {
	$completeTasks = [];
	    
	foreach ($lesson->getTasks() as $task) {
	    $isTaskComplete = $this->isTaskComplete($task, $completeSteps);			
	    if ($isTaskComplete) {
		$completeTasks[$task->getId()] = $task;   
	    }
	}
	
	return $completeTasks;
    }
    
    private function isTaskComplete(Task $task, Array $completeSteps) 
    {
	$isTaskComplete = TRUE;
		
	foreach ($task->getSteps() as $step) {
	    if (!isset($completeSteps[$step->getId()])) {
		$isTaskComplete = FALSE;
	    }
	}
	
	return $isTaskComplete;
    }
}
