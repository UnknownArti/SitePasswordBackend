<?php

require 'inc/DBCon.inc.php';
require 'inc/Datahandler.inc.php';

//Check if all required headers and params have been provided
$functionset = isset($_GET["function"]);
/*if(!($_SERVER['HTTP_USERNAME'])){
    die("No username provided.");
}else if(!($_SERVER['HTTP_PASSWORD'])){
    die("No password provided.");
}else */
if(!($functionset)){
    die("4062");
}

//Store the username and pasword from the headers to variables.
/*$username = $_SERVER['HTTP_USERNAME'];
$password = $_SERVER['HTTP_PASSWORD'];
*/

//Store the requested function from the url params into a variable.
$function = $_GET["function"];

//Open a new Mysql Server connection with the previously specified variables.
$connection = getConnection();

//Begin functionality of the class.
if($function == "login"){ //If the specified function is login.

    if(!($_SERVER['HTTP_USERNAME'])){
        die("4063");
    }else if(!($_SERVER['HTTP_PASSWORD'])){
        die("4064");
    }

    $username = $_SERVER['HTTP_USERNAME'];
    $password = $_SERVER['HTTP_PASSWORD'];

    $spassword;

    $query = "SELECT Password FROM userlogins WHERE username = ? OR email = ?"; //Specify the query we'll be using to recover the password.
    $loginstmt = $connection->prepare($query); //Prepare the statement.

    if (!$loginstmt->bind_param('ss', $username, $username)){ //Send the actual parameters to the Mysql server and check if we've been able to do so.
        die("5060"); //If the bind has failed, return "bind param failed".
    }

    $loginstmt->execute(); //Tell the Mysql server to execute the query.
    $loginstmt->store_result(); //Store the result produced by the query.
    $loginstmt->bind_result($spassword); //Bind the password value from the result of the query to a variable.
    $loginresult = $loginstmt->fetch(); //Fetch the result and store it in a variable

    if(!$loginresult){ //If loginresult is empty.
        die("4065"); //Kill the process and respond with "No Password Found".
    }

    $loginstmt->free_result(); //Free the result.
    $loginstmt->close(); //Close the statement.

    if(!crypt($password, $spassword) == $spassword){
        die("4066"); //Kill the process and output "Incorrect Password Provided".
    }

    $tokenquery = "SELECT authtoken FROM userlogins WHERE username = ?";
    $tokenstmt =  $connection->prepare($tokenquery);
        
    if(!$tokenstmt->bind_param('s', $username)){
        die("5060");
    }

    $tokenstmt->execute();
    $tokenstmt->store_result();
    $tokenstmt->bind_result($stoken);
    $tokenresult = $tokenstmt->fetch();

    if(!$tokenstmt){
        die("4067");
    }

    $tokenstmt->free_result();

    echo $stoken;
}else if($function == "adduser"){

    if(!$_SERVER['HTTP_AUTHTOKEN']){
        die("4060");
    }else if(!$_SERVER['HTTP_USER']){
        die("3061");
    }else if(!$_SERVER['HTTP_EMAIL']){
        die("3062");
    }else if(!$_SERVER['HTTP_PASSWORD']){
        die("3063");
    }else if(!$_SERVER['HTTP_LEVEL']){
        die("3064");
    }

    $authtoken = $_SERVER['HTTP_AUTHTOKEN'];
    $nusername = $_SERVER['HTTP_USER'];
    $nemail = $_SERVER['HTTP_EMAIL'];
    $npassword = $_SERVER['HTTP_PASSWORD'];
    $nlevel = $_SERVER['HTTP_LEVEL'];

    if(!t_active($authtoken)){
        die("4068");
    }

    if(t_verify($authtoken, 2) == true){
        //die(t_verify($authtoken, 2));
        t_adduser($nusername, $nemail, $npassword, $nlevel);
    }else{
        die("3060");
    }

}else if($function == "resetpassword"){
    echo $function;
}

//Debug
/*
echo "Connected Successfully.";
echo "<br>";
echo "Username: " . $username;
echo "<br>";
echo "Password: " . $password;
*/

?>