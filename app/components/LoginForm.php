<?php

class LoginForm extends \Nette\Application\UI\Control
{
    public $onUserLogin;
    
    private $authenticator;

    public function __construct(\MyAuthenticator $authenticator)
    {
        parent::__construct();
	
	$this->authenticator = $authenticator;
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
	    ->setRequired("Prosím vložte své uživatelské jméno.");
		
	$form->addPassword("password", "Heslo:")
	    ->setRequired("Prosím vložte své heslo");
		
	$form->addCheckbox("remember", "Zůstat trvale přihlášen.");
		
	$form->addSubmit("send", "Odeslat");
	$form->onSuccess[] = [$this, "processForm"];

        return $form;
    }

    public function processForm(\Nette\Application\UI\Form $form)
    {
        $values = $form->getValues();
	$user = $this->getPresenter()->getUser();
	
	try {
	    $user->setExpiration($values->remember ? "14 days" : "20 minutes");
	    $user->setAuthenticator($this->authenticator);
	    $user->login($values->name, $values->password);
	} catch (Nette\Security\AuthenticationException $e) {
	    $form->addError("Jméno nebo heslo se neshoduje.");
	    return;
	}
	
	$this->onUserLogin($this);
    }
}

interface ILoginFormFactory
{
    /** @return \LoginForm */
    function create();
}
