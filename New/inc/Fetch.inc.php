<?php

//Fetch The Access Level of a given token.
function fetch_level($accesstoken){
    require 'DBCon.inc.php';
    require 'Config.inc.php';

    $connection = getConnection();
    $query = "SELECT accesslevel FROM $dbname WHERE authtoken = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('s', $accesstoken);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($result);
    $res = $stmt->fetch();

    return $result;
}

//Fetch the username of a user identified by email, auth token or ID.
function fetch_username($param){
    require 'DBCon.inc.php';
    require 'Config.inc.php';

    $connection = getConnection();
    $query = "SELECT username FROM $dbname WHERE email = ? OR authtoken = ? OR ID = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('sss', $param, $param, $param);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($result);
    $res = $stmt->fetch();

    return $result;
}

//Fetch the email of a user identified by username, auth token or ID.
function fetch_email($param){
    require 'DBCon.inc.php';
    require 'Config.inc.php';

    $connection = getConnection();
    $query = "SELECT username FROM $dbname WHERE username = ? OR authtoken = ? OR ID = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('sss', $param, $param, $param);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($result);
    $res = $stmt->fetch();

    return $result;
}

//Fetch the auth token of a user identified by username, email or ID.
function fetch_token($param){
    require 'DBCon.inc.php';
    require 'Config.inc.php';

    $connection = getConnection();
    $query = "SELECT authtoken FROM $dbname WHERE username = ? OR email = ? OR ID = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('sss', $param, $param, $param);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($result);
    $res = $stmt->fetch();

    return $result;
}

?>