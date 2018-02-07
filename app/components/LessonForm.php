<?php

class LessonForm extends \Nette\Application\UI\Control
{
    public $onLessonSave;
    
    private $lessonId;
    private $lessonsModel;

    public function __construct($lessonId, \Model\LessonsModel $lessonsModel)
    {
        parent::__construct();
	
	$this->lessonId = $lessonId;
        $this->lessonsModel = $lessonsModel;
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
	
	$lesson = $this->lessonsModel->getLesson($this->lessonId);
        
	$form->addText("name", "Jméno lekce:")
		->setRequired("%label musíte vyplnit.")
		->addRule(Nette\Application\UI\Form::MAX_LENGTH, "%label může mít maximálně %value znaků.", 255);
	
	$form->addTextArea("description", "Popis:", 20, 10)
		->setRequired("%label musíte vyplnit.")
		->addRule(Nette\Application\UI\Form::MAX_LENGTH, "%label může mít maximálně %value znaků.", 1000);
	
	$form->addCheckbox("visible", "Zvěřejnit:");
	
	if ($lesson) {
	    $form["name"]->setDefaultValue($lesson->getName());
	    $form["description"]->setDefaultValue($lesson->getDescription());
	    $form["visible"]->setDefaultValue($lesson->getVisible());
	}

        $form->addSubmit("send", "Odeslat");
        $form->onSuccess[] = [$this, "processForm"];

        return $form;
    }

    public function processForm(\Nette\Application\UI\Form $form)
    {
        $values = $form->getValues();
	
	 if ($this->lessonId) {
	     $this->lessonsModel->updateLesson($values, $this->lessonId);
	 } else {
	    $this->lessonsModel->saveLesson($values);   
	 }
	 
	 $this->onLessonSave($this);
    }
}

interface ILessonFormFactory
{
    /** @return \LessonForm */
    function create($lessonId);
}
