<?php
//Include db.php
include (__DIR__ . '/db.php');

function addQCCecklist($woNumber){
    //Global variable for database
    global $db;

    //Inicalize results with bad result message
    $results = false;

    //Create PDO statement object
    $stmt = $db->prepare("INSERT INTO qc_checklist_table SET checklistID = :checklistID, woNum = :woNumber, item1 = :item1, item2 = :item2, item3 = :item3, item4 = :item4, item5 = :item5, item5A = :item5A, item5B = :item5B, item6 = :item6, item6A = :item6A, item6B = :item6B, item7 = :item7, item7A = :item7A, item7B = :item7B, item7C = :item7C, item8 = :item8, item8A = :item8A, item8B = :item8B, item8C = :item8C, item9 = :item9, item10 = :item10, item10A = :item10A, item11 = :item11, item12 = :item12, item13 = :item13, item14 = :item14, item15 = :item15, item16 = :item16, userID_Sig1 = :userID_Sig1, userID_Sig2 = :userID_Sig2, userID_Sig1_Filepath = :userID_Sig1_Filepath, userID_Sig2_Filepath = :userID_Sig2_Filepath");

    //Store SQL statement argumants in array
    $binds = array(
        ":checklistID" => $woNumber,
        ":woNumber" => $woNumber,
        ":item1" => 0,
        ":item2" => 0,
        ":item3" => 0,
        ":item4" => 0,
        ":item5" => 0,
        ":item5A" => "",
        ":item5B" => "",
        ":item6" => 0,
        ":item6A" => null,
        ":item6B" => "",
        ":item7" => 0,
        ":item7A" => null,
        ":item7B" => null,
        ":item7C" => null,
        ":item8" => 0,
        ":item8A" => "",
        ":item8B" => "",
        ":item8C" => null,
        ":item9" => 0,
        ":item10" => 0,
        ":item10A" => "",
        ":item11" => 0,
        ":item12" => 0,
        ":item13" => 0,
        ":item14" => 0,
        ":item15" => 0,
        ":item16" => 0,
        ":userID_Sig1" => null,
        ":userID_Sig2" => null,
        ":userID_Sig1_Filepath" => "",
        ":userID_Sig2_Filepath" => ""
    );
    
    //Check if PDO statement was executed and there is more then 1 row in table
    if ($stmt->execute($binds) && $stmt->rowCount() > 0) {
        $results = true;
    }

    //Return the results
    return ($results);
}

//Return the checklist record with the associated WO Num
function getQCChecklist($woNumber){
    global $db;

    $results = [];

    $sql = "SELECT * FROM qc_checklist_table WHERE woNum = :woNumber";

    $binds = array(
        ":woNumber" => $woNumber
    );

    $stmt = $db->prepare($sql);
    if($stmt->execute($binds) && $stmt->rowCount() > 0)
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

    return $results;
}

function updateQCChecklist($qcChecklist) {
    //Global variable for database
    global $db;

    //Inicalize results with bad result message
    $results = false;

    //Create PDO statement object
    $stmt = $db->prepare("UPDATE qc_checklist_table SET item1 = :item1, item2 = :item2, item3 = :item3, item4 = :item4, item5 = :item5, item5A = :item5A, item5B = :item5B, item6 = :item6, item6A = :item6A, item6B = :item6B, item7 = :item7, item7A = :item7A, item7B = :item7B, item7C = :item7C, item8 = :item8, item8A = :item8A, item8B = :item8B, item8C = :item8C, item9 = :item9, item10 = :item10, item10A = :item10A, item11 = :item11, item12 = :item12, item13 = :item13, item14 = :item14, item15 = :item15, item16 = :item16, userID_Sig1 = :userID_Sig1, userID_Sig2 = :userID_Sig2, userID_Sig1_Filepath = :userID_Sig1_Filepath, userID_Sig2_Filepath = :userID_Sig2_Filepath WHERE checklistID=:checklistID");
    
    //Store SQL statement argumants
    $binds = array(
        ":checklistID" => $qcChecklist["checklistID"],
        ":item1" => $qcChecklist["item1"],
        ":item2" => $qcChecklist["item2"],
        ":item3" => $qcChecklist["item3"],
        ":item4" => $qcChecklist["item4"],
        ":item5" => $qcChecklist["item5"],
        ":item5A" => $qcChecklist["item5A"],
        ":item5B" => $qcChecklist["item5B"],
        ":item6" => $qcChecklist["item6"],
        ":item6A" => $qcChecklist["item6A"],
        ":item6B" => $qcChecklist["item6B"],
        ":item7" => $qcChecklist["item7"],
        ":item7A" => $qcChecklist["item7A"],
        ":item7B" => $qcChecklist["item7B"],
        ":item7C" => $qcChecklist["item7C"],
        ":item8" => $qcChecklist["item8"],
        ":item8A" => $qcChecklist["item8A"],
        ":item8B" => $qcChecklist["item8B"],
        ":item8C" => $qcChecklist["item8C"],
        ":item9" => $qcChecklist["item9"],
        ":item10" => $qcChecklist["item10"],
        ":item10A" => $qcChecklist["item10A"],
        ":item11" => $qcChecklist["item11"],
        ":item12" => $qcChecklist["item12"],
        ":item13" => $qcChecklist["item13"],
        ":item14" => $qcChecklist["item14"],
        ":item15" => $qcChecklist["item15"],
        ":item16" => $qcChecklist["item16"],
        ":userID_Sig1" =>  (int)$qcChecklist["userID_Sig1"],
        ":userID_Sig2" => (int)$qcChecklist["userID_Sig2"],
        ":userID_Sig1_Filepath" => $qcChecklist["userID_Sig1_Filepath"],
        ":userID_Sig2_Filepath" => $qcChecklist["userID_Sig2_Filepath"]
    );

    //Check if PDO statement was executed and there is more then 1 row in table
    if ($stmt->execute($binds) && $stmt->rowCount() > 0) {
        //Make results equal good result message
        $results = true;
    }
    
    //Return the results
    return ($results);
}

function getVesselID($woNumber){
    global $db;

    $results = [];

    $sql = "SELECT vesselID FROM workorder_lookup WHERE woNum = :woNumber";

    $binds = array(
        ":woNumber" => $woNumber
    );

    $stmt = $db->prepare($sql);
    if($stmt->execute($binds) && $stmt->rowCount() > 0)
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

    return $results;
}
?>