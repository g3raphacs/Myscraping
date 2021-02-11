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

        $base->qw('INSERT INTO scrap(`scraplist_ID`, `name`, `url`)
                  VALUES (:scraplist_ID, "Nouvelle Collecte", "https://www.google.fr")',
        array(
            array('scraplist_ID',$scraplist,\PDO::PARAM_STR)
            )
        );
    }
}
