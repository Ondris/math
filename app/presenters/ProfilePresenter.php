<?php

namespace App\Presenters;

use Nette;


final class ProfilePresenter extends BasePresenter
{
    public function startup()
    {
        parent::startup();

        if (!$this->getUser()->isLoggedIn()) {
	    $this->flashMessage("Přistup odepřen.", "error");
            $this->redirect("Homepage:default");
        }
    }
    
    public function renderDefault()
    {
	$this->template->student = $this->usersModel->getUser("id", $this->getUser()->getId());
    }
    
}
