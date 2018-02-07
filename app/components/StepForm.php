<?php

class StepForm extends \Nette\Application\UI\Control
{
    public $onStepSave;
    
    private $stepId;
    private $taskId;
    private $stepsModel;

    public function __construct($stepId, $taskId, \Model\StepsModel $stepsModel)
    {
        parent::__construct();
	
	$this->stepId = $stepId;
	$this->taskId = $taskId;
        $this->stepsModel = $stepsModel;
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
	
	$step = $this->stepsModel->getStep($this->stepId);
	
	$form->addTextArea("question", "Otázka", 20, 5)
		->setRequired("%label musíte vyplnit.")
		->addRule(Nette\Application\UI\Form::MAX_LENGTH, "%label může mít maximálně %value znaků.", 1000);
	
	$form->addTextArea("answer", "Odpověď", 20, 5)
		->setRequired("%label musíte vyplnit.")
		->addRule(Nette\Application\UI\Form::MAX_LENGTH, "%label může mít maximálně %value znaků.", 1000);
	
	$form->addTextArea("help", "Nápověda:", 20, 5)
		->setRequired("%label musíte vyplnit.")
		->addRule(Nette\Application\UI\Form::MAX_LENGTH, "%label může mít maximálně %value znaků.", 1000);
	
	$form->addCheckbox("visible", "Zvěřejnit:");
	
	if ($step) {
	    $form["help"]->setDefaultValue($step->getHelp());
	    $form["visible"]->setDefaultValue($step->getVisible());
	}

        $form->addSubmit("send", "Odeslat");
        $form->onSuccess[] = [$this, "processForm"];

        return $form;
    }

    public function processForm(\Nette\Application\UI\Form $form)
    {
        $values = $form->getValues();
	
	 if ($this->stepId) {
	     $this->stepsModel->updateStep($values, $this->stepId);
	 } else {
	    $values["task_id"] = $this->taskId;
	    $this->stepsModel->saveStep($values);   
	 }
	 
	 $this->onStepSave($this);
    }
}

interface IStepFormFactory
{
    /** @return \StepForm */
    function create($stepId, $taskId);
}
