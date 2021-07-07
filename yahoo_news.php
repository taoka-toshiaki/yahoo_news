<?php
session_start();
class yahoo_{
        function main(){
        return new class{
            var $xpath = null;
            function gethtml($url){
                    $html = file_get_contents($url);
                    $dom = new DOMDocument();
                    $html = mb_convert_encoding($html, "HTML-ENTITIES", 'UTF-8');
                    @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                    $this->xpath = new DOMXPath($dom);
    
                    return new class($this->xpath){
                        var $xpath_obj = null;
                        function __construct($xpath){
                            $this->xpath_obj = $xpath;
                        }
                        function yahoo_news_it(){
                            $res =[];
                            foreach($this->xpath_obj->query("//*[@id=\"uamods-topics\"]/div/div/div/ul/li") as $obj){
                                $res[] = $obj->textContent;
                                }
                                return $res;
                        }
                    };
    
            }
        };
    }
}

$ret = [];
if($_SESSION["token"] === $_POST["token"]){
    $yahoo_ = new yahoo_();
    $ret["view"] = $yahoo_->main()->gethtml("https://news.yahoo.co.jp/categories/it")->yahoo_news_it();
}
print json_encode($ret);
