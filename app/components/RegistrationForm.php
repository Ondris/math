<?php

class RegistrationForm extends \Nette\Application\UI\Control
{
    public $onUserSave;
    
    private $usersModel;

    public function __construct(Model\UsersModel $usersModel)
    {
        parent::__construct();
	
	$this->usersModel = $usersModel;
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
	
	$form->addText("name", "Jméno:")
		->setRequired("%label musíte vyplnit.")
		->addRule(Nette\Application\UI\Form::MAX_LENGTH, "%label může mít maximálně %value znaků.", 255);
	
	$form->addPassword("password", "Heslo:")
		->setRequired("%label musíte vyplnit.");
	
	$form->addPassword("passwordVerify", "Potvrzení hesla:")
		->setRequired("Zadejte prosím heslo ještě jednou pro kontrolu")
		->addRule(\Nette\Application\UI\Form::EQUAL, "Hesla se neshodují", $form["password"]);

        $form->addSubmit("send", "Odeslat");
        $form->onSuccess[] = [$this, "processForm"];

        return $form;
    }

    public function processForm(\Nette\Application\UI\Form $form)
    {
        $values = $form->getValues();
	$password = \Nette\Security\Passwords::hash($values->password);
	
	$this->usersModel->saveUser($values->name, $password);
	 
	 $this->onUserSave($this);
    }
}

interface IRegistrationFormFactory
{
    /** @return \RegistrationForm */
    function create();
}
