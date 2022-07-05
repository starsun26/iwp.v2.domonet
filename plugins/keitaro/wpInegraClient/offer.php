<?php
header('Access-Control-Allow-Origin: *');


function debug($obj) 
{
    echo '<pre>';
    print_r($obj);
    echo '</pre>';

}

// debug($_REQUEST);

// echo debug(json_decode($_REQUEST['params']));

$params = json_decode($_REQUEST['params']);

$lp = file_get_contents('https://themusichabit.com/?_lp=1&_token='.$params->token);

if(count(explode('https://', $lp)) > 1) {
    // echo 'Redirect';
    // echo ' ';
    // echo $lp;
    $data = [
        "Redirect" => true,
        "lp" => $lp
    ];
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    $landUrl = 'https://quotes-me.com/';
    echo '<base href="'.$landUrl.'__Offers/'.$params->path.'/'.$lp.'/lp.php'.'">';
    // require_once '__Offers/'.$params->path.'/'.$lp.'/lp.php';
    echo file_get_contents($landUrl . '__Offers/'.$params->path.'/'.$lp.'/lp.php');
}