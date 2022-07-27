<?php 
//Include db.php
include (__DIR__ . '/db.php');

//Function to log user in
function login($userName, $userPassword){
    //Global variable for database
    global $db;

    $result = ["userID"=>"", "accountType"=>""];

    //Create PDO statement object
    $stmt = $db->prepare("SELECT userID, accountType FROM users_lookup WHERE userName=:userName AND userPassword=:userPassword");

    //Store SQL statement argumants
    $stmt->bindValue(':userName', $userName);
    $stmt->bindValue(':userPassword', sha1("LRSE-salt" . $userPassword));

    //Check if PDO statement was executed and there is more then 1 row in table
    if ($stmt->execute() && $stmt->rowCount() > 0 ) {
        //Get query results
        $result = $stmt->fetch(PDO::FETCH_ASSOC);           
    }

    //Return user record if one is found
    return($result);
}
?>