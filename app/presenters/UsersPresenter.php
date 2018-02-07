<?php

namespace App\Presenters;

use Nette;


final class UsersPresenter extends BasePresenter
{
    
    public function startup()
    {
        parent::startup();

        if (!$this->user->isInRole("admin")) {
	    $this->flashMessage("Přistup odepřen.", "error");
            $this->redirect("Homepage:default");
        }
    }
    
    public function handleChangeVisible($userId, $visible) 
    {
	$this->usersModel->changeVisible($userId, $visible);
	
	$this->flashMessage("Změna proběhla úspěšně.");
	$this->redirect("this");
    }
    
    public function renderDefault()
    {
	$this->template->students = $this->usersModel->getUsers();
    }
}