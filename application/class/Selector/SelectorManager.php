<?php

namespace App\Selector;
use App\Connexion\Connexion;


class SelectorManager {

    public static function updateSelector($id){
        echo $id;
        $base = new Connexion;

        $base->qw('UPDATE selector
                    SET `name` = :nm,
                        `format` = :ft,
                        `parent` = :pt,
                        `element` = :el
                        WHERE ID = :id',
        array(
            array('nm',$_POST['name'],\PDO::PARAM_STR),
            array('ft',$_POST['format'],\PDO::PARAM_STR),
            array('pt',$_POST['parent'],\PDO::PARAM_STR),
            array('el',$_POST['element'],\PDO::PARAM_STR),
            array('id',$id,\PDO::PARAM_INT)
            )
        );
    }
}
