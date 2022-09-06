<?php 
//Include db.php
include (__DIR__ . '/db.php');

function validateWONum($woNum){
    try{
        //Global variable for database
        global $db;

        $result = false;

        //Create PDO statement object
        $stmt = $db->prepare("SELECT woNum FROM WorkOrder_Lookup WHERE woNum=:woNum");

        //Store SQL statement argumants
        $stmt->bindValue(':woNum', $woNum);

        //Check if PDO statement was executed and there is more then 1 row in table
        if ($stmt->execute() && $stmt->rowCount() > 0 ) {
            //Get query results
            $result = true;           
        }

        //Return if work order number was found
        return($result);
    } catch (Exception $e){
        //Return false
        return(false);
    }
}

function validateVessle($vesselID){
    try{
        //Global variable for database
        global $db;

        $result = false;

        //Create PDO statement object
        $stmt = $db->prepare("SELECT vesselID FROM Customer_Vessels_Lookup WHERE vesselID=:vesselID");

        //Store SQL statement argumants
        $stmt->bindValue(':vesselID', $vesselID);

        //Check if PDO statement was executed and there is more then 1 row in table
        if ($stmt->execute() && $stmt->rowCount() > 0 ) {
            //Get query results
            $result = true;           
        }

        //Return if work order number was found
        return($result);
    } catch (Exception $e){
        //Return false
        return(false);
    }
}

function validateCustomer($customerID){
    try{
        //Global variable for database
        global $db;

        $result = false;

        //Create PDO statement object
        $stmt = $db->prepare("SELECT customerID FROM Customer_Lookup WHERE customerID=:customerID");

        //Store SQL statement argumants
        $stmt->bindValue(':customerID', $customerID);

        //Check if PDO statement was executed and there is more then 1 row in table
        if ($stmt->execute() && $stmt->rowCount() > 0 ) {
            //Get query results
            $result = true;           
        }

        //Return if work order number was found
        return($result);
    } catch (Exception $e){
        //Return false
        return(false);
    }
}
?>