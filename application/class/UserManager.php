<?php

namespace App;
use App\Connexion\Connexion;


class UserManager {
    private $username;
    private $password;
    private $confirm;
    private $mail;
    

    public function signin(){
        if (isset($_POST['username']) && isset($_POST['password']) ){
            $this->username = $_POST['username'];
            $this->password = $_POST['password'];
        
            $base = new Connexion;

            $req = $base->q(
                "SELECT
                            u.password
                            FROM user as u
                            WHERE u.username = :username",
                array(
                    array('username',$this->username,\PDO::PARAM_STR)
                    )
            );

            if(isset($req[0]->password)){
                $registeredPassword = $req[0]->password;
                if($this->password === $registeredPassword){
                    $this->openSession(); 
                }else{
                    $this->resp('Utilisateur ou mot de passe invalide' , 'null');
                }
            }else{
                $this->resp('Utilisateur ou mot de passe invalide' , 'null');
            }
            

        }else{
            $this->resp('Oups! un problème est survenu...' , 'null');
        }
    }

    private function openSession(){
        $this->resp('OK' , $this->username);
    }

    private function resp($status , $user){
        echo json_encode(array(
            'status' => $status,
            'user' => $user
        ));
    }


    public function signup(){


    }
}
