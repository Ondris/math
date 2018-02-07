<?php

namespace Model;

final class UsersModel extends BaseModel
{
    const TABLE = "users";
    
    public function getUser($column, $value)
    {
	$rows = $this->database->table(self::TABLE)
		->where("visible", 1);
	if ($column) {
	    $rows->where($column, $value);
	}
	$row = $rows->fetch();

	return $row ? new User($row) : NULL;
    }
    
    public function getUsers()
    {
	$rows = $this->database->table(self::TABLE);
	
	$users = [];
	foreach ($rows as $row) {
	    $users[] = new User($row);
	}
	
	return $users;
    }

    public function confirmUser($code)
    {
	return $this->database->table(self::TABLE)
		->where("code", $code)
		->update(["visible" => 1, "code" => ""]);
    }
    
    public function changeVisible($userId, $visible)
    {
	$data = [];
	if ($visible == User::VISIBLE) {
	    $data = ["visible" => User::VISIBLE, "code" => ""];
	} else {
	    $data = ["visible" => User::INVISIBLE, "code" => \Nette\Utils\Random::generate()];
	}
	
	$this->database->table(self::TABLE)
		->where("id", $userId)
		->update($data);
    }
    
    public function saveUser($name, $password)
    {
	$this->database->table(self::TABLE)
		->insert(["name" => $name, "password" => $password, "code" => \Nette\Utils\Random::generate()]);
    }
}
