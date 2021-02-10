<?php

namespace App\Session;

class SessionManager {
    
    public static function checkSession(){
        if(isset($_SESSION['scraplist'])) {
            return true;
        }
        return false;
    }
    public static function openSession($username , $scraplist){
        $_SESSION["username"]=$username;
        $_SESSION["scraplist"]=$scraplist;
    }
    public static function killSession(){
        if (isset($_SESSION['username'])) {
            unset($_SESSION["username"]);
        }
        if (isset($_SESSION['scraplist'])) {
            unset($_SESSION["scraplist"]);
        }
        session_destroy();
    }
}