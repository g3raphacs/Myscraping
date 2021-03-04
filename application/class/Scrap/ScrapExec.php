<?php

namespace App\Scrap;

use Goutte\Client;
use App\Connexion\Connexion;


class ScrapExec {

    private $scrapID;
    private $dateID;
    private $selectors;
    private $selectorsCount;
    private $params;
    private $basedata = [];
    private $singles = [];

    function __construct($id) {
        $this->scrapID = $id;
        $date = date('Ymd');
        $base = new Connexion;
        $base->qw('INSERT INTO scrapdate(`scrapID`, `date`)
                  VALUES (:scrapID, :dat)',
        array(
            array('scrapID',$id,\PDO::PARAM_INT),
            array('dat',$date,\PDO::PARAM_INT)
            )
        );
        $req = $base->q(
            "SELECT max(ID) as ID FROM scrapdate", null
        );
        $this->dateID =  $req[0]->ID;
        $this->getSelectors($this->scrapID);
        $this->getParams($this->scrapID);
        $this->getData();
        if ($this->basedata){
            $this->createSingles();
        }else{
            echo "no data scraped - -//- - ";
        }
        var_dump($this->singles);
        if($this->singles){
            $this->createElements();
        }else{
            "no scrap single found - -//- - ";
        }
    }

    private function getData(){
        $url = $this->params->url;
        for ($i =0 ; $i<$this->selectorsCount ; $i++){
            echo $this->selectors[$i]->element."----->";
            $css_selector = $this->selectors[$i]->element;
            // $thing_to_scrape = array("href" , "src" , "class" , "_text");
            $thing_to_scrape = array("_text");
            $client = new Client();
            $crawler = $client->request('GET', $url);
            $output = $crawler->filter($css_selector)->extract($thing_to_scrape);
            if($output){
                $this->basedata[] = $output;
            }else{
                echo "no matching element - -//- - ";
            }
            
        }
    }

    private function createSingles(){
        $base = new Connexion;
        for($i = 0 ; $i<count($this->basedata[0]) ; $i++){
            $base->qw('INSERT INTO scrapsingle(`scrapdate_id`)
                  VALUES (:scrapdate_id)',
            array(
                array('scrapdate_id',$this->dateID,\PDO::PARAM_INT)
                )
            );
            $req = $base->q(
                "SELECT max(ID) as ID FROM scrapsingle", null
            );
            $this->singles[] = (int)($req[0]->ID);
        }
    }

    private function createElements(){
        
        $base = new Connexion;
        for($i = 0 ; $i<$this->selectorsCount ; $i++){
            for($j = 0 ; $j<count($this->singles) ; $j++){
                $base->qw('INSERT INTO scrapelement(`name`, `format`, `data` ,`scrapSingle_ID`)
                  VALUES (:nm, :ft, :dat , :scpid)',
                array(
                    array('nm', $this->selectors[$i]->name,\PDO::PARAM_STR),
                    array('ft', $this->selectors[$i]->format,\PDO::PARAM_STR),
                    array('dat', $this->basedata[$i][$j],\PDO::PARAM_STR),
                    array('scpid', $this->singles[$j],\PDO::PARAM_INT)
                    )
                );
            }
        }
    }

    private function getParams($id){
        $base = new Connexion;

        $req = $base->q(
            "SELECT     *
                        FROM scrap as s
                        WHERE s.ID = :id",
            array(
                array('id',$id,\PDO::PARAM_INT)
                )
        );
        if(isset($req)){
            $this->params = $req[0];
        }else{
            echo 'no scrap found';
        }
    }



    private function getSelectors($id){
        $base = new Connexion;

            $req = $base->q(
                "SELECT     *
                            FROM selector as s
                            WHERE s.scrapID = :id
                            ORDER BY s.ID DESC",
                array(
                    array('id',$id,\PDO::PARAM_INT)
                    )
            );
            if(isset($req)){
                $this->selectors = $req;
                $this->selectorsCount = count($this->selectors);
            }else{
                echo 'no selector found';
            }
    }

    // public function launchScrap($id){
    //     $selectors = getSelectors($id);
    //     getData();
    // }
}
