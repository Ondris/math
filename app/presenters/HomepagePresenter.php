<?php

namespace App\Presenters;

use Nette;


final class HomepagePresenter extends BasePresenter
{
    private $lessonsModel;
    private $tasksModel;
    private $usersStepsModel;

    public function injectFinal(\Model\LessonsModel $lessonsModel, \Model\TasksModel $tasksModel, \Model\UsersStepsModel $usersStepsModel)
    {
	$this->lessonsModel = $lessonsModel;
	$this->tasksModel = $tasksModel;
	$this->usersStepsModel = $usersStepsModel;
    }
    
    public function renderDefault()
    {
	$this->template->lessons = $this->lessonsModel->getLessons(1);
    }
    
    public function renderLesson($lessonId) 
    {
	$this->template->lesson = $this->lessonsModel->getLesson($lessonId);
    }
    
    public function renderTask($taskId, $numberOfSteps = 1, $result = "")
    {
	$this->template->numberOfSteps = $numberOfSteps;
	$this->template->result = $result;
	
	$this->template->task = $this->tasksModel->getTask($taskId);
    }
    
    function createComponentAnswerForm()
    {
	$form = new \Nette\Application\UI\Form();

	$form->addText("answer");
	$form->addHidden("numberOfSteps");

	$form->addSubmit("send");
	$form->onSuccess[] = [$this, "processForm"];

	return $form;
    }
    
    public function processForm(Nette\Application\UI\Form $form) 
    {
	$values = $form->getValues();
	$taskId = $this->getParameter("taskId");
	
	$task = $this->tasksModel->getTask($taskId);
	$step = $task->getStep($values->numberOfSteps);
	
	$result = $this->getResult($step, $values);
	
	$this->redirect("Homepage:task", ["taskId" => $taskId, "numberOfSteps" => $values->numberOfSteps + 1, "result" => $result]);
    }
    
    private function getResult(\Model\Step $step, Nette\Utils\ArrayHash $values) 
    {
	if ($step->getAnswer() === $values->answer) {
	    if ($this->getUser()->isLoggedIn()) {
		$this->usersStepsModel->save($this->getUser()->getId(), $step->getId());
	    }
	    return $result = $values->answer . "<br>Správně!";
	} else {
	    return $values->answer . "<br>Nesprávně<br>". $step->getHelp();
	}
    }
    
}
