<?php

namespace App\Presenters;

use Nette;


abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    protected $usersModel;

    public function injectBase(\Model\UsersModel $usersModel)
    {
	$this->usersModel = $usersModel;
    }
    
    public function handleLogout()
    {
	$this->user->logout();
	
	$this->flashMessage("Odhlášení proběhlo úspěšně.");
	$this->redirect("Homepage:");
    }
}
