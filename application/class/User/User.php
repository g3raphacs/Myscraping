<?php

namespace App\User;

class User {
    
    public function doReq(){
        echo json_encode($_POST['username']." ".$_POST['password']);
    }
}