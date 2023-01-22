<?php

/**
 * Search with different engines
 *
 * PHP version 8.1.10
 *
 * @category   SearchEngine
 * @package    searchengine
 * @author     liamgen.js <liamgen.js@gmail.com>
 * @copyright  2023 - Liamgen, Inc.
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    1.0.0
*/

namespace Search;

class Engine
{
    public $engine = Null;
    public $params = Null;


    public function __construct($engine, $params)
    {
        $this->params = $params;
        $this->init_engine($engine);
    }

    private function init_engine($eng){
        $engines = new Engines();
        $engines = $engines->get();

        if(!array_key_exists($eng, $engines)){
            return trigger_error("Engine not defined",E_USER_WARNING);
        }

        $this->engine = $engines[$eng];

        foreach($this->engine["require_key"] as $v){
            if(!array_key_exists($v, $this->params)){
                return trigger_error($v." is required",E_USER_WARNING);
            }
        }
    }

    public function search($q, $params=Array()){
        if(!$this->engine){
            echo "1";
            return $this->search($q, $params);
        }
        $q = urlencode($q);
        $params["q"] = $q;
        $endpoint = $this->parser($this->engine["api_endpoint"], $params);

        $res = $this->request($endpoint);

        if(array_key_exists("error", $res)){
            return $res["error"]["code"];
        }

        $e = $this->engine["return"];
        foreach($e as $v){
            $res = $res[$v];
        }

        return $res;
    }

    private function parser($url, $params){
        $elements = Array(
            "search" => $params["q"],
            "api_key" => array_key_exists("api_key", $this->params) ? $this->params["api_key"] : Null,
            "cx" => array_key_exists("cx", $this->params) ? $this->params["cx"] : Null,
            "lang" => array_key_exists("lang", $params) ? $params["lang"] : "en",
        );

        foreach($elements as $k=>$v){
            if($k && $v){
                $url = str_replace("{".$k."}", $v, $url);
            }
        }

        return $url;
    }

    private function request($url){
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);

        curl_close($curl);
        $res = json_decode($resp, true);
        return $res;
    }
}