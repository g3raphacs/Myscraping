<?php

namespace App\User;

use App\User\UserManager;
use App\Entity;

class User extends Entity {

    private $id;
    private $username;
    private $scraplist;
    private $mail;
    private $password;
    
    public function __construct($id)
    {
        $data = UserManager::initUser($id);
        $this->hydrate($data);
    }

    public function execHydrate(array $data)
    {
        foreach ($data as $key => $value)
        {
            $method = 'set_'.$key;
            if(method_exists($this, $method))
            {
                $this->$method($value);
            }    
        }
    }

    public function get_id(){ return $this->id; }
    protected function set_id($id)
    {
        $this->id = $id;
        return $this;
    }

    public function get_password(){ return $this->password; }
    protected function set_password($password)
    {
        $this->password = $password;
        return $this;
    }

    public function get_mail(){ return $this->mail; }
    protected function set_mail($mail)
    {
        $this->mail = $mail;
        return $this;
    }
    public function get_scraplist(){ return $this->scraplist; }
    protected function set_scraplist($scraplist)
    {
        $this->scraplist = $scraplist;
        return $this;
    }
    public function get_username(){ return $this->username; }
    protected function set_username($username)
    {
        $this->username = $username;
        return $this;
    }

}