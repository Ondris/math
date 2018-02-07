<?php

namespace Model;

final class Step extends \BaseEntity
{
    public function getId()
    {
        return $this->row->id;
    }
    
    public function getQuestion() 
    {
	return $this->row->question;
    }
    
    public function getAnswer()
    {
	return $this->row->answer;
    }

    public function getHelp()
    {
	return $this->row->help;
    }
    
    public function getTask() 
    {
	$row = $this->row->ref(TasksModel::TABLE, "task_id");
	
	return $row ? new Task($row) : FALSE;
    }
    
    public function getVisible()
    {
	return $this->row->visible;
    }
    
    public function getOrder()
    {
	return $this->row->order;
    }
}
