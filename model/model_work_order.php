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
    $stmt = $db->prepare("INSERT INTO wo_items_table SET woNum = :woNumber, quantity = :quantity, productID = :productID");

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

    $sql = "SELECT customerName FROM customer_lookup WHERE customerID = :customerID";

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

    $sql = "SELECT serialNumber FROM customer_vessels_lookup WHERE vesselID = :vesselID";

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

    $sql = "SELECT * FROM stage_key_lookup WHERE 0=0";

    $stmt = $db->prepare($sql);
    if($stmt->execute() && $stmt->rowCount() > 0)
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

function getWorkOrder($woNumber){
    global $db;

    $results = [];

    $sql = "SELECT * FROM workorder_lookup WHERE woNum = :woNumber";

    $binds = array(
        ":woNumber" => $woNumber
    );

    $stmt = $db->prepare($sql);
    if($stmt->execute($binds) && $stmt->rowCount() > 0)
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

    return $results;
}


function getCutomer($customerID){
    global $db;

    $results = [];

    $sql = "SELECT * FROM customer_lookup WHERE customerID = :customerID";

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

    $sql = "SELECT * FROM customer_vessels_lookup WHERE vesselID = :vesselID";

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

    $sql = "SELECT * FROM vessel_lookup WHERE vesselModel = :vesselModel";

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

    $sql = "SELECT * FROM wo_items_table WHERE woNum = :woNumber";

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

    $sql = "SELECT * FROM product_lookup WHERE productID = :productID";

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
    $stmt = $db->prepare("UPDATE workorder_lookup SET stageKey = :stageKey WHERE woNum=:woNumber");
    
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

    $sql = "SELECT * FROM workorder_lookup WHERE 0=0";
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
    $stmt = $db->prepare("DELETE FROM qc_checklist_table WHERE woNum=:woNumber");
    $stmt->bindValue(':woNumber', $woNumber);
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        //Make results equal good result message
        $results = true;
    }

    $stmt = $db->prepare("DELETE FROM component_table WHERE woNum=:woNumber");
    $stmt->bindValue(':woNumber', $woNumber);
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        //Make results equal good result message
        $results = true;
    }

    $stmt = $db->prepare("DELETE FROM inspection_worksheet_table WHERE worksheetID=:woNumber");
    $stmt->bindValue(':woNumber', $woNumber);
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        //Make results equal good result message
        $results = true;
    }

    $stmt = $db->prepare("DELETE FROM changelog_table WHERE woNum=:woNumber");
    $stmt->bindValue(':woNumber', $woNumber);
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        //Make results equal good result message
        $results = true;
    }

    $stmt = $db->prepare("DELETE FROM dated_items_table WHERE woNum=:woNumber");
    $stmt->bindValue(':woNumber', $woNumber);
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        //Make results equal good result message
        $results = true;
    }

    $stmt = $db->prepare("DELETE FROM wo_items_table WHERE woNum=:woNumber");
    $stmt->bindValue(':woNumber', $woNumber);
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        //Make results equal good result message
        $results = true;
    }

    $stmt = $db->prepare("DELETE FROM survival_table WHERE woNum=:woNumber");
    $stmt->bindValue(':woNumber', $woNumber);
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        //Make results equal good result message
        $results = true;
    }

    $stmt = $db->prepare("DELETE FROM workorder_lookup WHERE woNum=:woNumber");
    $stmt->bindValue(':woNumber', $woNumber);
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        //Make results equal good result message
        $results = true;
    }
    
    //Return the results
    return ($results);
}

?>