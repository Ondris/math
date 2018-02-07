<?php
use Nette\Security as NS;

class MyAuthenticator extends Nette\Object implements NS\IAuthenticator
{
    public $usersModel;

    function __construct(\Model\UsersModel $usersModel)
    {
        $this->usersModel = $usersModel;
    }

    public function authenticate(array $credentials)
    {
        list($name, $password) = $credentials;
        $user = $this->usersModel->getUser("name", $name);

        if (!$user) {
            throw new NS\AuthenticationException("Uživatel neexistuje.");
        }
	
        if (!NS\Passwords::verify($password, $user->getPassword())) {
            throw new NS\AuthenticationException("Neplatné heslo.");
        }

        return new NS\Identity($user->getId(), $user->getRole(), ["username" => $user->getName()]);
    }
}