<?php

class TaskForm extends \Nette\Application\UI\Control
{
    public $onTaskSave;
    
    private $taskId;
    private $lessonId;
    private $tasksModel;

    public function __construct($taskId, $lessonId, \Model\TasksModel $tasksModel)
    {
        parent::__construct();
	
	$this->taskId = $taskId;
	$this->lessonId = $lessonId;
        $this->tasksModel = $tasksModel;
    }
    
    public function render()
    {
	$template = $this->template;
	$template->setFile(__DIR__ . "/form.latte");
	$template->render();
    }

    protected function createComponentForm()
    {
        $form = new \Nette\Application\UI\Form();
	
	$task = $this->tasksModel->getTask($this->taskId);
	
	$form->addTextArea("description", "Popis:", 20, 10)
		->setRequired("%label musíte vyplnit.")
		->addRule(Nette\Application\UI\Form::MAX_LENGTH, "%label může mít maximálně %value znaků.", 1000);
	
	$form->addCheckbox("visible", "Zvěřejnit:");
	
	if ($task) {
	    $form["description"]->setDefaultValue($task->getDescription());
	    $form["visible"]->setDefaultValue($task->getVisible());
	}

        $form->addSubmit("send", "Odeslat");
        $form->onSuccess[] = [$this, "processForm"];

        return $form;
    }

    public function processForm(\Nette\Application\UI\Form $form)
    {
        $values = $form->getValues();

	 if ($this->taskId) {
	     $this->tasksModel->updateTask($values, $this->taskId);
	 } else {
	    $values["lesson_id"] = $this->lessonId;
	    $this->tasksModel->saveTask($values);   
	 }
	 
	 $this->onTaskSave($this);
    }
}

interface ITaskFormFactory
{
    /** @return \TaskForm */
    function create($taskId, $lessonId);
}
