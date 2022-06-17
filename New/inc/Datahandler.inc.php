<?php

//Functions
function b_crypt($input){

    require "Config.inc.php";

    $salt = "";
    $salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
    for($i=0; $i < 22; $i++){
        $salt .= $salt_chars[array_rand($salt_chars)];
    }
    return crypt($input, sprintf('$2a$%02d$', $hash_rounds) . $salt);
}

function t_verify($token, $RequiredLevel){

    require 'DBCon.inc.php';
    require 'Config.inc.php';

    //Open a new Mysql Server connection with the previously specified variables.
    $connection = getConnection();

    $authq = "SELECT accesslevel FROM userlogins WHERE authtoken = ?";
    $authstmt = $connection->prepare($authq);
    $authstmt->bind_param('s', $token);

    $authstmt->execute();
    $authstmt->store_result();
    $authstmt->bind_result($slevel);
    $authresult = $authstmt->fetch();

    return ($slevel >= $RequiredLevel);
    $tokenstmt->free_result;
}

function t_active($token){

    require 'DBCon.inc.php';
    require 'Config.inc.php';

    //Open a new Mysql Server connection with the previously specified variables.
    $connection = getConnection();

    $authq = "SELECT active FROM $dbname WHERE authtoken = ?";
    $authstmt = $connection->prepare($authq);

    if(!$authstmt->bind_param('s', $token)){
        die("5060");
    }

    $authstmt->execute();
    $authstmt->store_result();
    $authstmt->bind_result($sactive);
    $authresult = $authstmt->fetch();

    return ($sactive == 1);
    $tokenstmt->free_result;
}

function t_adduser($tusername, $temail, $tpassword, $tlevel){

    require 'DBCon.inc.php';
    require 'Config.inc.php';

    //Open a new Mysql Server connection with the previously specified variables.
    $connection = getConnection();

    //Check that the username and email are unique.
    $existsq = "SELECT * FROM $dbname WHERE username = ? OR email = ?";
    $existsstmt = $connection->prepare($existsq);
    if (!$existsstmt->bind_param('ss', $tusername, $temail)){ //Send the actual parameters to the Mysql server and check if we've been able to do so.
        die("5060"); //If the bind has failed, return "bind param failed".
    }
    $existsstmt->execute();

    $bresult = $existsstmt->fetch();
    if($bresult !== null){
        die("4061");
    }
    $existsstmt->free_result();
    $existsstmt->close();

    //Hash the provided password.
    $tpassword_hash = b_crypt($tpassword);

    //Generate a unique ID to be used as the authtoken.
    $uid = uniqid();

    //Add the user to the database.
    $addq = "INSERT INTO $dbname (username, email, password, authtoken, active, accesslevel) VALUES (?, ?, ?, ?, 1, ?)";
    $addstmt = $connection->prepare($addq);
    $addstmt->bind_param("sssss", $tusername, $temail, $tpassword_hash, $uid, $tlevel);
    $addstmt->execute();
    echo "9000"; 
}

?>