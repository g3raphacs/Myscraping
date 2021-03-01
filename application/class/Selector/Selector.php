<?php

namespace App\Selector;

use App\Entity;
use App\Connexion\Connexion;

class Selector extends Entity{

    private $id;
    private $name;
    private $format;
    private $parent;
    private $element;
    
    public function __construct($data)
    {
        $this->hydrate($data);
    }

    public function get_name(){ return $this->name; }
    protected function set_name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function get_format(){ return $this->format; }
    protected function set_format($format)
    {
        $this->format = $format;
        return $this;
    }

    public function get_parent(){ return $this->parent; }
    protected function set_parent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function get_element(){ return $this->element; }
    protected function set_element($element)
    {
        $this->element = $element;
        return $this;
    }


    public function addToDB($id){

        $base = new Connexion;
        $base->qw('INSERT INTO selector(`name`, `format`, `parent` ,`element`, `scrapID`)
                  VALUES (:nm, :ft, :pt , :el, :id)',
        array(
            array('nm', $this->name,\PDO::PARAM_STR),
            array('ft', $this->format,\PDO::PARAM_STR),
            array('pt', $this->parent,\PDO::PARAM_STR),
            array('el', $this->element,\PDO::PARAM_STR),
            array('id',$id,\PDO::PARAM_INT)
            )
        );
    }
    public function getValues($id){

        $base = new Connexion;
        $req = $base->q(
            "SELECT
                        *
                        FROM selector as s
                        WHERE s.scrapID = :ID",
            array(
                array('ID',$id,\PDO::PARAM_INT)
                ));
        if(isset($req)){
            return $req;
        }else{
            return 'no scrap found';
        }
    }
    public static function delToDB($id){
        $base = new Connexion;

        $base->qw('DELETE FROM selector WHERE ID = :ID',
        array(
            array('ID',$id,\PDO::PARAM_INT)
            )
        );
    }
}