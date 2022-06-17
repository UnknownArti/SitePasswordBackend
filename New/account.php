<?php

require 'inc/Fetch.inc.php'

if(!$_SERVER['HTTP_AUTHTOKEN']){
    die("4060");
}else if(!isset($_GET['function'])){
    die("4062");
}

$function = $_GET['function'];
$atoken = $_SERVER['HTTP_AUTHTOKEN'];

if($function == "ausername"){
    echo fetch_username($atoken);
}else if($function == "aemail"){
    echo fetch_email($atoken);
}else if($function == "alevel"){
    echo fetch_level($atoken);
}

?>