<?php

namespace App\Presenters;

use Nette;


final class AdminPresenter extends BasePresenter
{
    private $lessonsModel;
    private $lessonFormFactory;
    
    private $tasksModel;
    private $taskFormFactory;
    
    private $stepsModel;
    private $stepFormFactory;

    public function injectFinal(\Model\LessonsModel $lessonsModel, \ILessonFormFactory $lessonFormFactory, \Model\TasksModel $tasksModel, 
	    \ITaskFormFactory $taskFormFactory, \Model\StepsModel $stepsModel, \IStepFormFactory $stepFormFactory)
    {
	$this->lessonsModel = $lessonsModel;
	$this->lessonFormFactory = $lessonFormFactory;
	
	$this->tasksModel = $tasksModel;
	$this->taskFormFactory = $taskFormFactory;
	
	$this->stepsModel = $stepsModel;
	$this->stepFormFactory = $stepFormFactory;
    }
    
    public function startup()
    {
        parent::startup();

        if (!$this->user->isInRole("admin")) {
	    $this->flashMessage("Přistup odepřen.", "error");
            $this->redirect("Homepage:default");
        }
    }
    
    public function renderDefault()
    {
	$this->template->lessons = $this->lessonsModel->getLessons(0);
    }
    
    public function renderEditLesson($lessonId) 
    {
	$this->template->lesson = $this->lessonsModel->getLesson($lessonId);
    }
    
    protected function createComponentLessonForm() 
    {
	$id = $this->getParameter("lessonId");
	$form = $this->lessonFormFactory->create($id);
	
	$form->onLessonSave[] = function (\LessonForm $lessonForm) {
	    $this->flashMessage("Úprava lekcí proběhla úspěšně.", "success");
	    $this->redirect("default");
	};

	return $form;
    }
    
    public function renderEditTask($lessonId, $taskId) 
    {
	$this->template->lesson = $this->lessonsModel->getLesson($lessonId);
	$this->template->task = $this->tasksModel->getTask($taskId);
    }
    
    protected function createComponentTaskForm()
    {
	$taskId = $this->getParameter("taskId");
	$lessonId = $this->getParameter("lessonId");
	$form = $this->taskFormFactory->create($taskId, $lessonId);
	
	$form->onTaskSave[] = function (\TaskForm $taskForm) {
	    $this->flashMessage("Úprava úloh proběhla úspěšně.", "success");
	    $this->redirect("default");
	};
	
	return $form;
    }
    
    public function renderEditStep($taskId, $stepId)
    {
	$this->template->task = $this->tasksModel->getTask($taskId);
	$this->template->step = $this->stepsModel->getStep($stepId);
    }
    
    protected function createComponentStepForm()
    {
	$stepId = $this->getParameter("stepId");
	$taskId = $this->getParameter("taskId");
	$form = $this->stepFormFactory->create($stepId, $taskId);
	
	$form->onStepSave[] = function (\StepForm $stepForm) {
	    $this->flashMessage("Úprava kroků proběhla úspěšně.", "success");
	    $this->redirect("default");
	};
	
	return $form;
    }
}
