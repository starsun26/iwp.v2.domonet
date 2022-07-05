<?php
function ktClo ($url, $token)
{
    if(explode('/',$_SERVER['REQUEST_URI'])[1] === $url)
    {
        
        require_once YOUR_PLUGIN_DIR . 'keitaro/wpInegraClient/kclient.php';
        $client = new KClient('https://themusichabit.com/api.php?', $token);
        $client->sendAllParams();
        require_once YOUR_PLUGIN_DIR . 'keitaro/wpInegraClient/index.php';
    }
}