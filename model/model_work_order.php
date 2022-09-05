<?php
//Include db.php
include (__DIR__ . '/db.php');

function addWorkOrder($customerID, $vesselID, $poNumber, $woDate, $estCompetion, $terms, $rep, $writtenBy){
    //Global variable for database
    global $db;

    //Inicalize results with bad result message
    $results = false;

    //Create PDO statement object
    $stmt = $db->prepare("INSERT INTO WorkOrder_Lookup SET customerID = :customerID, vesselID = :vesselID, poNum = :poNumber, woDateCreated = :woDate, woEstCompletion = :estCompetion, terms = :terms, rep = :rep, writtenBy = :writtenBy, pdfFilepath = :pdfFilepath, complete = :complete, stageKey = :stageKey");

    //Store SQL statement argumants in array
    $binds = array(
        ":customerID" => $customerID,
        ":vesselID" => $vesselID,
        ":poNumber" => $poNumber,
        ":woDate" => $woDate,
        ":estCompetion" => $estCompetion,
        ":terms" => $terms,
        ":rep" => $rep,
        ":writtenBy" => $writtenBy,
        ":pdfFilepath" => "",
        ":complete" => 0,
        ":stageKey" => 0
    );
    
    //Check if PDO statement was executed and there is more then 1 row in table
    if ($stmt->execute($binds) && $stmt->rowCount() > 0) {
        $stmt = $db->prepare("SELECT woNum FROM WorkOrder_Lookup WHERE customerID = :customerID AND vesselID = :vesselID AND poNum = :poNumber AND woDateCreated = :woDate AND woEstCompletion = :estCompetion AND terms = :terms AND rep = :rep AND writtenBy = :writtenBy AND pdfFilepath = :pdfFilepath AND complete = :complete AND stageKey = :stageKey");
        if ($stmt->execute($binds) && $stmt->rowCount() > 0){
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    //Return the results
    return ($results);
}

function addWorkOrderItem($woNumber, $quantity, $productID){
    //Global variable for database
    global $db;

    //Inicalize results with bad result message
    $results = false;

    //Create PDO statement object
    $stmt = $db->prepare("INSERT INTO WO_Items_Table SET woNum = :woNumber, quantity = :quantity, productID = :productID");

    //Store SQL statement argumants in array
    $binds = array(
        ":woNumber" => $woNumber,
        ":quantity" => $quantity,
        ":productID" => $productID
    );
    
    //Check if PDO statement was executed and there is more then 1 row in table
    if ($stmt->execute($binds) && $stmt->rowCount() > 0) {
        $results = true;
    }

    //Return the results
    return ($results);
}

function getCustomerName($customerID){
    global $db;

    $results = [];

    $sql = "SELECT customerName FROM Customer_Lookup WHERE customerID = :customerID";

    $binds = array(
        ":customerID" => $customerID
    );

    $stmt = $db->prepare($sql);
    if($stmt->execute($binds) && $stmt->rowCount() > 0)
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

    return $results;
}

function getVesselSerialNumber($vesselID){
    global $db;

    $results = [];

    $sql = "SELECT serialNumber FROM Customer_Vessels_Lookup WHERE vesselID = :vesselID";

    $binds = array(
        ":vesselID" => $vesselID
    );

    $stmt = $db->prepare($sql);
    if($stmt->execute($binds) && $stmt->rowCount() > 0)
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

    return $results;
}

function getProducts(){
    global $db;

    $results = [];

    $sql = "SELECT * FROM Product_Lookup WHERE 0=0";

    $stmt = $db->prepare($sql);
    if($stmt->execute() && $stmt->rowCount() > 0)
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}


//View Work Order Functions
function getStageKeys(){
    global $db;

    $results = [];

    $sql = "SELECT * FROM Stage_Key_Lookup WHERE 0=0";

    $stmt = $db->prepare($sql);
    if($stmt->execute() && $stmt->rowCount() > 0)
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

function getWorkOrder($woNumber){
    try{
        global $db;

        $results = [];

        $sql = "SELECT * FROM WorkOrder_Lookup WHERE woNum = :woNumber";

        // $binds = array(
        //     ":woNumber" => $woNumber
        // );

        $stmt = $db->prepare($sql);
        if($stmt->execute($binds) && $stmt->rowCount() > 0)
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e){
        header("Location: home.php" );
        echo 'Caught exception: ',  $e->getMessage(), "\n"; 
    }
    return $results;
}


function getCutomer($customerID){
    global $db;

    $results = [];

    $sql = "SELECT * FROM Customer_Lookup WHERE customerID = :customerID";

    $binds = array(
        ":customerID" => $customerID
    );

    $stmt = $db->prepare($sql);
    if($stmt->execute($binds) && $stmt->rowCount() > 0)
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

    return $results;
}

function getVessel($vesselID){
    global $db;

    $results = [];

    $sql = "SELECT * FROM Customer_Vessels_Lookup WHERE vesselID = :vesselID";

    $binds = array(
        ":vesselID" => $vesselID
    );

    $stmt = $db->prepare($sql);
    if($stmt->execute($binds) && $stmt->rowCount() > 0)
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

    return $results;
}

function getVesselMenufacture($vesselModel){
    global $db;

    $results = [];

    $sql = "SELECT * FROM Vessel_Lookup WHERE vesselModel = :vesselModel";

    $binds = array(
        ":vesselModel" => $vesselModel
    );

    $stmt = $db->prepare($sql);
    if($stmt->execute($binds) && $stmt->rowCount() > 0)
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

    return $results;
}

function getItems($woNumber){
    global $db;

    $results = [];

    $sql = "SELECT * FROM WO_Items_Table WHERE woNum = :woNumber";

    $binds = array(
        ":woNumber" => $woNumber
    );

    $stmt = $db->prepare($sql);
    if($stmt->execute($binds) && $stmt->rowCount() > 0)
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

function getProduct($productID){
    global $db;

    $results = [];

    $sql = "SELECT * FROM Product_Lookup WHERE productID = :productID";

    $binds = array(
        ":productID" => $productID
    );

    $stmt = $db->prepare($sql);
    if($stmt->execute($binds) && $stmt->rowCount() > 0)
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

    return $results;
}

function updateStageKey($woNumber, $stageKey) {
    //Global variable for database
    global $db;

    //Inicalize results with bad result message
    $results = false;

    //Create PDO statement object
    $stmt = $db->prepare("UPDATE WorkOrder_Lookup SET stageKey = :stageKey WHERE woNum=:woNumber");
    
    //Store SQL statement argumants
    $stmt->bindValue(':stageKey', $stageKey);
    $stmt->bindValue(':woNumber', $woNumber);

    //Check if PDO statement was executed and there is more then 1 row in table
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        //Make results equal good result message
        $results = true;
    }
    
    //Return the results
    return ($results);
}

function searchWorkOrders($searchField){
    include('./model/model_customer.php');
    $tempVessel = new Vessel;

    global $db;

    $results = [];

    $sql = "SELECT * FROM WorkOrder_Lookup WHERE 0=0";
    $binds = [];

    $stmt = $db->prepare($sql);
    if($stmt->execute($binds) && $stmt->rowCount() > 0)
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    for($i = 0; $i < count($results); $i++){
        $results[$i]["customerName"] = getCustomerName($results[$i]["customerID"])["customerName"];
        $customerVessels = $tempVessel->getCustomerVessels($results[$i]["customerID"]);
       
        for($j = 0; $j < count($customerVessels); $j++){
            if($customerVessels[$j]["vesselID"] == $results[$i]["vesselID"]){
                $results[$i]["vesselManufacturer"] = $customerVessels[$j]["vesselManufacturer"];
            }
        }
    }


    return $results;
}

function deleteWO($woNumber) {
    //Global variable for database
    global $db;
    
    //Inicalize results with bad result message
    $results = false;
    
    //Create PDO statement object
    $stmt = $db->prepare("DELETE FROM QC_Checklist_Table WHERE woNum=:woNumber");
    $stmt->bindValue(':woNumber', $woNumber);
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        //Make results equal good result message
        $results = true;
    }

    $stmt = $db->prepare("DELETE FROM Component_Table WHERE woNum=:woNumber");
    $stmt->bindValue(':woNumber', $woNumber);
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        //Make results equal good result message
        $results = true;
    }

    $stmt = $db->prepare("DELETE FROM Inspection_Worksheet_Table WHERE worksheetID=:woNumber");
    $stmt->bindValue(':woNumber', $woNumber);
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        //Make results equal good result message
        $results = true;
    }

    $stmt = $db->prepare("DELETE FROM ChangeLog_Table WHERE woNum=:woNumber");
    $stmt->bindValue(':woNumber', $woNumber);
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        //Make results equal good result message
        $results = true;
    }

    $stmt = $db->prepare("DELETE FROM Dated_Items_Table WHERE woNum=:woNumber");
    $stmt->bindValue(':woNumber', $woNumber);
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        //Make results equal good result message
        $results = true;
    }

    $stmt = $db->prepare("DELETE FROM WO_Items_Table WHERE woNum=:woNumber");
    $stmt->bindValue(':woNumber', $woNumber);
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        //Make results equal good result message
        $results = true;
    }

    $stmt = $db->prepare("DELETE FROM Survival_Table WHERE woNum=:woNumber");
    $stmt->bindValue(':woNumber', $woNumber);
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        //Make results equal good result message
        $results = true;
    }

    $stmt = $db->prepare("DELETE FROM WorkOrder_Lookup WHERE woNum=:woNumber");
    $stmt->bindValue(':woNumber', $woNumber);
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        //Make results equal good result message
        $results = true;
    }
    
    //Return the results
    return ($results);
}

?>