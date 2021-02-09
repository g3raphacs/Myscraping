<?php

namespace App;

class User {
    
    public function doReq(){
        echo json_encode($_POST['username']." ".$_POST['password']);
    }
}