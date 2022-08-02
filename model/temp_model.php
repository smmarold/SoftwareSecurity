<?php
//Include db.php
include (__DIR__ . '/db.php');

//customers.php functions
function getCustomers($search){
    global $db;

    $results = [];
    $binds = array();

    $sql = "SELECT customerID, customerName, customerAddress, customerAddress2, customerCity, customerState, customerZipCode, customerEmail, customerPhone FROM Customer_Lookup WHERE 0=0";
    if ($search != ""){
        $sql .= " AND customerName LIKE :customerName";
        $binds['customerName'] = '%'.$search.'%';
    }

    $stmt = $db->prepare($sql);
    if($stmt->execute($binds) && $stmt->rowCount() > 0)
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

//inspectionSheet.php functions
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

function getWorkOrder($woNumber){
    global $db;

    $results = [];

    $sql = "SELECT * FROM WorkOrder_Lookup WHERE woNum = :woNumber";

    $binds = array(
        ":woNumber" => $woNumber
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

function getPSISection($woNumber){
    global $db;

    $results = [];

    $sql = "SELECT * FROM Inspection_Worksheet_Table WHERE worksheetID = :woNumber";

    $binds = array(
        ":woNumber" => $woNumber
    );

    $stmt = $db->prepare($sql);
    if($stmt->execute($binds) && $stmt->rowCount() > 0)
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

    return $results;
}

function addPsiSection($woNumber, $sheetID){
    //Global variable for database
    global $db;

    //Inicalize results with bad result message
    $results = false;

    //Create PDO statement object
    $stmt = $db->prepare("INSERT INTO Inspection_Worksheet_Table SET worksheetID = :woNumber, sheetID = :sheetID, serviceDate = :serviceDate, appovalNum = :appovalNum, URPSI = :URPSI, URReliefOpen = :URReliefOpen, URReliefReseat = :URReliefReseat, URTimeOn = :URTimeOn, URTimeOff = :URTimeOff, URPressureOn = :URPressureOn, URPressureOff = :URPressureOff, URTemperatureOn = :URTemperatureOn, URTemperatureOff = :URTemperatureOff, URBarometerOn = :URBarometerOn, URBarometerOff = :URBarometerOff, URFinishPressure = :URFinishPressure, URCorrectedPressure = :URCorrectedPressure, URPassFail = :URPassFail, LRPSI = :LRPSI, LRReliefOpen = :LRReliefOpen, LRReliefReseat = :LRReliefReseat, LRTimeOn = :LRTimeOn, LRTimeOff = :LRTimeOff, LRPressureOn = :LRPressureOn, LRPressureOff = :LRPressureOff, LRTemperatureOn = :LRTemperatureOn, LRTemperatureOff = :LRTemperatureOff, LRBarometerOn = :LRBarometerOn, LRBarometerOff = :LRBarometerOff, LRFinishPressure = :LRFinishPressure, LRCorrectedPressure = :LRCorrectedPressure, LRPassFail = :LRPassFail, FLPSI = :FLPSI, FLReliefOpen = :FLReliefOpen, FLReliefReseat = :FLReliefReseat, FLTimeOn = :FLTimeOn, FLTimeOff = :FLTimeOff, FLPressureOn = :FLPressureOn, FLPressureOff = :FLPressureOff, FLTemperatureOn = :FLTemperatureOn, FLTemperatureOff = :FLTemperatureOff, FLBarometerOn = :FLBarometerOn, FLBarometerOff = :FLBarometerOff, FLFinishPressure = :FLFinishPressure, FLCorrectedPressure = :FLCorrectedPressure, FLPassFail = :FLPassFail, FiveYearInflation = :FiveYearInflation, FloorStrength = :FloorStrength, NAP = :NAP, ReleaseHook = :ReleaseHook, LoadTest = :LoadTest, CylinderSerialA = :CylinderSerialA, CylinderSerialB = :CylinderSerialB, GrossWeightA = :GrossWeightA, GrossWeightB = :GrossWeightB, WeightCO2A = :WeightCO2A, WeightCO2B = :WeightCO2B, WeightN2A = :WeightN2A, WeightN2B = :WeightN2B, HydroTestDueDateA = :HydroTestDueDateA, HydroTestDueDateB = :HydroTestDueDateB, Comments = :Comments");

    //Store SQL statement argumants in array
    $binds = array(
        ":woNumber" => $woNumber,
        ":sheetID" => $sheetID,
        ":serviceDate" => null,
        ":appovalNum" => "",
        ":URPSI" => "",
        ":URReliefOpen" => "",
        ":URReliefReseat" => "",
        ":URTimeOn" => "",
        ":URTimeOff" => "",
        ":URPressureOn" => "",
        ":URPressureOff" => "",
        ":URTemperatureOn" => "",
        ":URTemperatureOff" => "",
        ":URBarometerOn" => "",
        ":URBarometerOff" => "",
        ":URFinishPressure" => "",
        ":URCorrectedPressure" => "",
        ":URPassFail" => "",
        ":LRPSI" => "",
        ":LRReliefOpen" => "",
        ":LRReliefReseat" => "",
        ":LRTimeOn" => "",
        ":LRTimeOff" => "",
        ":LRPressureOn" => "",
        ":LRPressureOff" => "",
        ":LRTemperatureOn" => "",
        ":LRTemperatureOff" => "",
        ":LRBarometerOn" => "",
        ":LRBarometerOff" => "",
        ":LRFinishPressure" => "",
        ":LRCorrectedPressure" => "",
        ":LRPassFail" => "",
        ":FLPSI" => "",
        ":FLReliefOpen" => "",
        ":FLReliefReseat" => "",
        ":FLTimeOn" => "",
        ":FLTimeOff" => "",
        ":FLPressureOn" => "",
        ":FLPressureOff" => "",
        ":FLTemperatureOn" => "",
        ":FLTemperatureOff" => "",
        ":FLBarometerOn" => "",
        ":FLBarometerOff" => "",
        ":FLFinishPressure" => "",
        ":FLCorrectedPressure" => "",
        ":FLPassFail" => "",
        ":FiveYearInflation" => "",
        ":FloorStrength" => "",
        ":NAP" => "",
        ":ReleaseHook" => "",
        ":LoadTest" => "",
        ":CylinderSerialA" => "",
        ":CylinderSerialB" => "",
        ":GrossWeightA" => "",
        ":GrossWeightB" => "",
        ":WeightCO2A" => "",
        ":WeightCO2B" => "",
        ":WeightN2A" => "",
        ":WeightN2B" => "",
        ":HydroTestDueDateA" => "",
        ":HydroTestDueDateB" => "",
        ":Comments" => ""
    );
    
    //Check if PDO statement was executed and there is more then 1 row in table
    if ($stmt->execute($binds) && $stmt->rowCount() > 0) {
        $results = true;
    }

    //Return the results
    return ($results);
}

function updatePsiSection($psiSection) {
    //Global variable for database
    global $db;

    //Inicalize results with bad result message
    $results = false;

    //Create PDO statement object
    $stmt = $db->prepare("UPDATE Inspection_Worksheet_Table SET  serviceDate = :serviceDate, appovalNum = :appovalNum, URPSI = :URPSI, URReliefOpen = :URReliefOpen, URReliefReseat = :URReliefReseat, URTimeOn = :URTimeOn, URTimeOff = :URTimeOff, URPressureOn = :URPressureOn, URPressureOff = :URPressureOff, URTemperatureOn = :URTemperatureOn, URTemperatureOff = :URTemperatureOff, URBarometerOn = :URBarometerOn, URBarometerOff = :URBarometerOff, URFinishPressure = :URFinishPressure, URCorrectedPressure = :URCorrectedPressure, URPassFail = :URPassFail, LRPSI = :LRPSI, LRReliefOpen = :LRReliefOpen, LRReliefReseat = :LRReliefReseat, LRTimeOn = :LRTimeOn, LRTimeOff = :LRTimeOff, LRPressureOn = :LRPressureOn, LRPressureOff = :LRPressureOff, LRTemperatureOn = :LRTemperatureOn, LRTemperatureOff = :LRTemperatureOff, LRBarometerOn = :LRBarometerOn, LRBarometerOff = :LRBarometerOff, LRFinishPressure = :LRFinishPressure, LRCorrectedPressure = :LRCorrectedPressure, LRPassFail = :LRPassFail, FLPSI = :FLPSI, FLReliefOpen = :FLReliefOpen, FLReliefReseat = :FLReliefReseat, FLTimeOn = :FLTimeOn, FLTimeOff = :FLTimeOff, FLPressureOn = :FLPressureOn, FLPressureOff = :FLPressureOff, FLTemperatureOn = :FLTemperatureOn, FLTemperatureOff = :FLTemperatureOff, FLBarometerOn = :FLBarometerOn, FLBarometerOff = :FLBarometerOff, FLFinishPressure = :FLFinishPressure, FLCorrectedPressure = :FLCorrectedPressure, FLPassFail = :FLPassFail, FiveYearInflation = :FiveYearInflation, FloorStrength = :FloorStrength, NAP = :NAP, ReleaseHook = :ReleaseHook, LoadTest = :LoadTest, CylinderSerialA = :CylinderSerialA, CylinderSerialB = :CylinderSerialB, GrossWeightA = :GrossWeightA, GrossWeightB = :GrossWeightB, WeightCO2A = :WeightCO2A, WeightCO2B = :WeightCO2B, WeightN2A = :WeightN2A, WeightN2B = :WeightN2B, HydroTestDueDateA = :HydroTestDueDateA, HydroTestDueDateB = :HydroTestDueDateB, Comments = :Comments WHERE worksheetID = :worksheetID");
    
    //Store SQL statement argumants
    $binds = array(
        ":worksheetID" => $psiSection["worksheetID"],
        ":serviceDate" => $psiSection["serviceDate"],
        ":appovalNum" => $psiSection["appovalNum"],
        ":URPSI" => $psiSection["URPSI"],
        ":URReliefOpen" => $psiSection["URReliefOpen"],
        ":URReliefReseat" => $psiSection["URReliefReseat"],
        ":URTimeOn" => $psiSection["URTimeOn"],
        ":URTimeOff" => $psiSection["URTimeOff"],
        ":URPressureOn" => $psiSection["URPressureOn"],
        ":URPressureOff" => $psiSection["URPressureOff"],
        ":URTemperatureOn" => $psiSection["URTemperatureOn"],
        ":URTemperatureOff" => $psiSection["URTemperatureOff"],
        ":URBarometerOn" => $psiSection["URBarometerOn"],
        ":URBarometerOff" => $psiSection["URBarometerOff"],
        ":URFinishPressure" => $psiSection["URFinishPressure"],
        ":URCorrectedPressure" => $psiSection["URCorrectedPressure"],
        ":URPassFail" => $psiSection["URPassFail"],
        ":LRPSI" => $psiSection["LRPSI"],
        ":LRReliefOpen" => $psiSection["LRReliefOpen"],
        ":LRReliefReseat" => $psiSection["LRReliefReseat"],
        ":LRTimeOn" => $psiSection["LRTimeOn"],
        ":LRTimeOff" => $psiSection["LRTimeOff"],
        ":LRPressureOn" => $psiSection["LRPressureOn"],
        ":LRPressureOff" => $psiSection["LRPressureOff"],
        ":LRTemperatureOn" => $psiSection["LRTemperatureOn"],
        ":LRTemperatureOff" => $psiSection["LRTemperatureOff"],
        ":LRBarometerOn" => $psiSection["LRBarometerOn"],
        ":LRBarometerOff" => $psiSection["LRBarometerOff"],
        ":LRFinishPressure" => $psiSection["LRFinishPressure"],
        ":LRCorrectedPressure" => $psiSection["LRCorrectedPressure"],
        ":LRPassFail" => $psiSection["LRPassFail"],
        ":FLPSI" => $psiSection["FLPSI"],
        ":FLReliefOpen" => $psiSection["FLReliefOpen"],
        ":FLReliefReseat" => $psiSection["FLReliefReseat"],
        ":FLTimeOn" => $psiSection["FLTimeOn"],
        ":FLTimeOff" => $psiSection["FLTimeOff"],
        ":FLPressureOn" => $psiSection["FLPressureOn"],
        ":FLPressureOff" => $psiSection["FLPressureOff"],
        ":FLTemperatureOn" => $psiSection["FLTemperatureOn"],
        ":FLTemperatureOff" => $psiSection["FLTemperatureOff"],
        ":FLBarometerOn" => $psiSection["FLBarometerOn"],
        ":FLBarometerOff" => $psiSection["FLBarometerOff"],
        ":FLFinishPressure" => $psiSection["FLFinishPressure"],
        ":FLCorrectedPressure" => $psiSection["FLCorrectedPressure"],
        ":FLPassFail" => $psiSection["FLPassFail"],
        ":FiveYearInflation" => $psiSection["FiveYearInflation"],
        ":FloorStrength" => $psiSection["FloorStrength"],
        ":NAP" => $psiSection["NAP"],
        ":ReleaseHook" => $psiSection["ReleaseHook"],
        ":LoadTest" => $psiSection["LoadTest"],
        ":CylinderSerialA" => $psiSection["CylinderSerialA"],
        ":CylinderSerialB" => $psiSection["CylinderSerialB"],
        ":GrossWeightA" => $psiSection["GrossWeightA"],
        ":GrossWeightB" => $psiSection["GrossWeightB"],
        ":WeightCO2A" => $psiSection["WeightCO2A"],
        ":WeightCO2B" => $psiSection["WeightCO2B"],
        ":WeightN2A" => $psiSection["WeightN2A"],
        ":WeightN2B" => $psiSection["WeightN2B"],
        ":HydroTestDueDateA" => $psiSection["HydroTestDueDateA"],
        ":HydroTestDueDateB" => $psiSection["HydroTestDueDateB"],
        ":Comments" => $psiSection["Comments"]
    );

    //Check if PDO statement was executed and there is more then 1 row in table
    if ($stmt->execute($binds) && $stmt->rowCount() > 0) {
        //Make results equal good result message
        $results = true;
    }
    
    //Return the results
    return ($results);
}
?>