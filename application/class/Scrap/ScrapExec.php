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
        $this->createSingles();
    }

    private function getData(){
        $url = $this->params->url;
        for ($i =0 ; $i<$this->selectorsCount ; $i++){
            $css_selector = $this->selectors[$i]->element;
            // $thing_to_scrape = array("href" , "src" , "class" , "_text");
            $thing_to_scrape = array("_text");
            $client = new Client();
            $crawler = $client->request('GET', $url);
            $output = $crawler->filter($css_selector)->extract($thing_to_scrape);
            $this->basedata[] = $output;
        }
    }

    private function createSingles(){
        for($i = 0 ; $i<count($this->basedata[0]) ; $i++){
            $base = new Connexion;
            $base->qw('INSERT INTO scrapsingle(`scrapdate_id`)
                  VALUES (:scrapdate_id)',
            array(
                array('scrapdate_id',$this->dateID,\PDO::PARAM_INT)
                )
            );
            $req = $base->q(
                "SELECT max(ID) as ID FROM scrapsingle", null
            );
            $this->createElements($req[0]->ID);
        }
    }

    private function createElements($id){
        echo "create elements for".$id;
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
