<?php

namespace App\Scrap;

use App\Entity;

class Scrap extends Entity{

    private $id;
    private $scraplist_ID;
    private $name;
    private $url;
    
    public function __construct($data)
    {
        $this->hydrate($data);
    }

    public function get_id(){ return $this->id; }
    protected function set_id($id)
    {
        $this->id = $id;
        return $this;
    }

    public function get_scraplist_ID(){ return $this->scraplist_ID; }
    protected function set_scraplist_ID($scraplist_ID)
    {
        $this->scraplist_ID = $scraplist_ID;
        return $this;
    }

    public function get_name(){ return $this->name; }
    protected function set_name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function get_url(){ return $this->url; }
    protected function set_url($url)
    {
        $this->url = $url;
        return $this;
    }

}