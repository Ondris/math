<?php

namespace App\Presenters;

use Nette;


final class SignPresenter extends BasePresenter
{
    private $loginFormFactory;

    public function injectFinal(\ILoginFormFactory $loginFormFactory) {
	$this->loginFormFactory = $loginFormFactory;
    }
    
    public function renderDefault()
    {
	
    }
    
    public function createComponentLoginForm()
    {
	$form = $this->loginFormFactory->create();
	
	$form->onUserLogin[] = function (\LoginForm $loginForm) {
	    $this->flashMessage("Přihlášení proběhlo úspěšně.", "success");
	    $this->redirect("Homepage:default");
	};

	return $form;
    }
    
}
