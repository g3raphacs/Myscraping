<?php

namespace App;
use App\Connexion\Connexion;
use Respect\Validation\Validator as v;


class UserManager {

    public function signin(){
        if ($this->checkUsername($_POST['username']) && $this->checkPassword($_POST['password']) ){
        
            $base = new Connexion;

            $req = $base->q(
                "SELECT
                            u.password
                            FROM user as u
                            WHERE u.username = :username",
                array(
                    array('username',$_POST['username'],\PDO::PARAM_STR)
                    )
            );

            if(isset($req[0]->password)){
                $registeredPassword = $req[0]->password;
                if(password_verify($_POST['password'], $registeredPassword)) {
                    $this->openSession(); 
                }else{
                    $this->resp('Utilisateur ou mot de passe invalide');
                }
            }else{
                $this->resp('Utilisateur ou mot de passe invalide');
            }
            

        }else{
            $this->resp('Oups! un problème est survenu...');
        }
    }

    private function openSession(){
        $this->resp('OK' , $_POST['username']);
    }

    private function resp($status , $user = null){
        echo json_encode(array(
            'status' => $status,
            'user' => $user
        ));
    }


    public function signup(){
        $validation = $this->signupValidation();
        if($validation === "OK"){
            $hashedPass = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $base = new Connexion;

            $base->qw('INSERT INTO user(`mail`, `username`, `password`)
                      VALUES (:mail, :username, :pass)',
            array(
                array('mail',$_POST['mail'],\PDO::PARAM_STR),
                array('username',$_POST['username'],\PDO::PARAM_STR),
                array('pass',$hashedPass,\PDO::PARAM_STR)
                )
            );
            $this->resp('OK');

        }else{
            $this->resp($validation);
        }
    }

    private function signupValidation(){
        if ($this->checkMail($_POST['mail'])){
            if ($this->checkUsername($_POST['username'])){
                if ($this->checkPassword($_POST['password']) && $this->checkPassword($_POST['passwordconf'])){
                    if($_POST['password'] === $_POST['passwordconf']){
                        if(!$this->checkUsernameExist()){
                            if(!$this->checkMailExist()){
                                return 'OK';
                            }else{
                                return "Cette adresse e-mail est deja utilisée";
                            }
                        }else{
                            return "Ce nom d'utilisateur est deja pris";
                        }
                    }else{
                        return "Le mot de passe et la confirmation sont différents";
                    }
                }else{
                    return "Mot de passe invalide";
                }
            }else{
                return "Nom d'utilisateur invalide";
            }
        }else{
            return 'Adresse e-mail invalide';
        } 
    }

    private function checkUsernameExist(){
        $base = new Connexion;

            $req = $base->q(
                "SELECT
                            u.username
                            FROM user as u
                            WHERE u.username = :username",
                array(
                    array('username',$_POST['username'],\PDO::PARAM_STR)
                    )
            );

            if(isset($req[0]->username)){
                return true;
            }else{
                return false;
            }
    }

    private function checkMailExist(){
        $base = new Connexion;

            $req = $base->q(
                "SELECT
                            u.mail
                            FROM user as u
                            WHERE u.mail = :mail",
                array(
                    array('mail',$_POST['mail'],\PDO::PARAM_STR)
                    )
            );

            if(isset($req[0]->mail)){
                return true;
            }else{
                return false;
            }
    }

    private function checkUsername($username){
        if(isset($username) && !empty($username)){
            if(v::stringVal()->validate($username)){
                return true;
            }
        }
        return false;
    }
    private function checkMail($mail){
        if(isset($mail) && !empty($mail)){
            if(v::email()->validate($mail)){
                return true;
            }
        }
        return false;
    }
    private function checkPassword($password){
        if(isset($password) && !empty($password)){
            return true;
        }
        return false;
    }
}
