<?php

//Handle creating a Database Connection

function getConnection(){

    require 'Config.inc.php';

    //Open a new Mysql Server connection with the previously specified variables.
    $connection = new mysqli($dbaddress, $dbuser, $dbpassword, $dbname);

    //Ensure that the utf8 charset is being used
    mysqli_set_charset($connection, "utf8");

    //Check if there is a connection error and output the error if there is.
    if ($connection->connect_error) {
        die("5062");
    }
    return $connection;
}

?>