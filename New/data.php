<?php

require 'inc/Config.inc.php';
//require 'inc/DBCon.inc.php';
require 'inc/Datahandler.inc.php';

if(!$_SERVER['HTTP_AUTHTOKEN']){
    die("4060");
}

$authtoken = $_SERVER['HTTP_AUTHTOKEN'];

if(!t_verify($authtoken, 1)){
    die("4060");
}

$connection2 = getConnection();

if(isset($_GET['search'])){
    $query = "SELECT * FROM logins WHERE ? LIKE ?";
}else{
    $search = $_GET['search'];
}

if(isset($_GET['field'])){
    $query = "SELECT * FROM logins WHERE ? LIKE ?";
    $stmt = $connection2->prepare($query);
    $stmt->bind_param('ss', $field, $search);
}else{
    $query = "SELECT * FROM logins";
    $stmt = $connection2->prepare($query);
}

$array = array();

$stmt->execute();

$result = $connection2->store_result();

if($result == false){
    die("5061");
}

if($result == null){
    die("5061");
}

while($row = $result->fetch_object()) {
        $array[] = $row;
}
echo json_encode($myArray);

?>