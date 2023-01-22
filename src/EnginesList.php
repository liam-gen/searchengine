<?php

/**
 * Get search engines propreties
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

namespace Search\Engines;

class Engines{
    public $engines = array(
        "google" => array(
            "require_key" => array("api_key", "cx"),
            "api_endpoint" => "https://www.googleapis.com/customsearch/v1?key={api_key}&q={search}&cx={cx}&lr={lang}&gl={lang}",
            "return" => array("items")
        ),
        "duckduckgo" => array(
            "require_key" => FALSE,
            "api_endpoint" => "https://api.duckduckgo.com/?q={search}&format=json&kl={lang}-{lang}",
            "return" => array("RelatedTopics")
        )
    );

    public function get(){
        return $this->engines;
    }
}