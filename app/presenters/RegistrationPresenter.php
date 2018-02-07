<?php

namespace App\Presenters;

use Nette;


final class RegistrationPresenter extends BasePresenter
{
    private $registrationFormFactory;

    public function injectFinal(\IRegistrationFormFactory $registrationFormFactory)
    {
	$this->registrationFormFactory = $registrationFormFactory;
    }
    
    public function renderDefault()
    {
	
    }
    
    protected function createComponentRegistrationForm() 
    {
	$form = $this->registrationFormFactory->create();
	
	$form->onUserSave[] = function (\RegistrationForm $registrationForm) {
	    $this->flashMessage("Uživatel byl úspěšně vytvořen. Nyní ho musíte aktivovat.", "success");
	    $this->redirect("default");
	};

	return $form;
    }
    
    public function renderConfirm($code)
    {
	$this->template->confirm = $this->usersModel->confirmUser($code);
    }
    
}
