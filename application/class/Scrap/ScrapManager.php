<?php

namespace App\Scrap;
use App\Connexion\Connexion;


class ScrapManager {
    public static function findScraps($scraplist){
        $base = new Connexion;

            $req = $base->q(
                "SELECT
                            *
                            FROM scrap as s
                            WHERE s.scraplist_ID = :scraplist
                            ORDER BY s.ID DESC",
                array(
                    array('scraplist',$scraplist,\PDO::PARAM_STR)
                    )
            );
            if(isset($req)){
                return $req;
            }else{
                return 'no scrap found';
            }
    }
    public static function newScrap($scraplist){
    
        
        $base = new Connexion;
        $req = $base->q(
            "SELECT
                        ID
                        FROM user
                        WHERE scraplist = :scraplist",
            array(
                array('scraplist',$scraplist,\PDO::PARAM_STR)
                )
        );
        $userID =  $req[0]->ID;

        $base->qw('INSERT INTO scrap(`scraplist_ID`, `name`, `url` ,`frequence`, `type`, `user_ID`)
                  VALUES (:scraplist_ID, "Nouvelle Collecte", "https://www.google.fr" ,"jour", "jeu" ,:user)',
        array(
            array('scraplist_ID',$scraplist,\PDO::PARAM_STR),
            array('user',$userID,\PDO::PARAM_INT)
            )
        );
    }
    public static function delete($id){
        $base = new Connexion;

        $base->qw('DELETE FROM scrap WHERE ID = :ID',
        array(
            array('ID',$id,\PDO::PARAM_INT)
            )
        );
    }
}
