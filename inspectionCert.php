<?php
    include('includes/header.php');
    include('model/temp_model.php');
    include('model/model_inspection_sheet.php');
    include('includes/functions.php');

    /* ***************************************************************************************************************************************************
    *
    * This page functionally works like the Inspection Sheet page, except there is no changing or filling. Only displaying saved Data. 
    * Formatting on this page is specific to the size of the paper it will be printed on. 
    * On Load, the print dialogue opens immediately, and once that dialogue is closed for any reason, we return to the inspection sheet page. 
    *
    ******************************************************************************************************************************************************/
    date_default_timezone_set('America/New_York');

    $partsChecklist = new PartsChecklist;
    $sheetExists = false;
    $isPCList = true;

    if(!isset($_GET["woNumber"])){ //Again, no woNum in URL means should be here, go home. 
        header("Location: home.php");
    }
    elseif($_SESSION["accountType"] != "Admin" && $_SESSION["accountType"] != 'Supervisor'){
        header("Location: viewWorkOrder.php?woNumber={$_GET["woNumber"]}");
    }
    //No POST. Only get, filling appropriate arrays. 
    if(isGetRequest()){

        $psiSection = getPSISection($_GET["woNumber"]);

        if($psiSection == [])
        {
            $sheetID = "INS".$_GET["woNumber"];
            addPsiSection($_GET["woNumber"], $sheetID);
            $psiSection = getPSISection($_GET["woNumber"]);
        }

        $woDetials = getWorkOrder($_GET["woNumber"]);
        $customerDetails = getCutomer($woDetials["customerID"]);
        $vesselDetails = getVessel($woDetials["vesselID"]);
        $vesselManufactureDetails = getVesselMenufacture($vesselDetails["vesselModel"]);
        
        $woNum = $_GET["woNumber"];
        $componentValues = $partsChecklist->getComponentsList($woNum);
        for($i = 0; $i < count($componentValues); $i++){
            if($componentValues[$i]["sheetID"] == "INS" .$woNum)
            {
                $tempComp = $componentValues[$i];
                $componentValues = [];
                $componentValues[0] = $tempComp;
                $isPCList = false;
            }
        }
        if(count($componentValues) > 0){
            $sheetExists = true;
            $survivalValues = $partsChecklist->getSurvivalList($componentValues[0]["sheetID"]);
            $datedItemsValues = $partsChecklist->getDatedItems($componentValues[0]["sheetID"]);
        }
        else{
            $survivalValues = [];
            $datedItemsValues = [];           
        }


    }
?>

<!DOCTYPE html>
<html lang="en" class="bg-white h-100">
<head>
  <title>LRSE Service Manager</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/print.css">
  <link rel="stylesheet" href="css/custom.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap');
    hr{
      height: 2px;
    }
    body, html{
      font-family: 'Lato' !important;
      font-size: 7px;
    }
    .hide{
        visibility: hidden;
    }
    .textSmall{
        font-size: 4px;
    }
  </style>

</head>
<body  onload="fillPartsChecklist()" class="h-100">
    <div class="container d-flex flex-column justify-content-between bg-white h-100">
        <div class="text-center d-flex justify-content-around align-items-center my-3">
            <img src="images/LRSE_Logo.png" height="75" width="150"/>
            <h4 class="text-center pt-3 pb-3"><b>Test and Survey Report<br>and<br>Re-Inspection Certificate</b></h4>
        </div>
        <div id="heading" class="text-center d-flex justify-content-between align-items-center mb-2">
            <div class="text-start">
                <p class="mx-3 my-0">590 Fish Road</p>
                <p class="mx-3 my-0">Tiverton, Rhode Island 02878</p>
            </div>
            <div class="text-center">
                <p class="mx-3 my-0">401-816-5400 Fax: 401-816-5411</p>
                <p class="mx-3 my-0">www.LRSE.com</p>
            </div>
            <div class="text-end">
                <p class="mx-3 my-0">Work Order #: <?= $woDetials["woNum"] ?></p>
                <p class="mx-3 my-0">Vessel: <?= $vesselDetails["vesselID"] ?></p>
            </div>
        </div>
        <form action="inspectionSheet.php" method="post">
            <div id="topSection" class="border text-black d-flex justify-content-around align-items-center">
                <div>
                    <input type="hidden" id="hiddenAction" name="action" value="<?= $woDetials["vesselID"]; ?>"/>
                    <input type="hidden" id="customerID" name="customerID" value="<?= $woDetials["customerID"]; ?>"/>
                </div>
                <div class="column psiColumn">
                    <div class="text-start my-1 psiItem">
                        <p class="label m-0 font-weight-bold"><b>Customer/Owner</b></p>
                        <p id="customer" class="m-0"><?= $customerDetails["customerName"] ?></p>
                    </div>
                    <div class="text-start my-1 psiItem">
                        <p class="label m-0 font-weight-bold"><b>Address</b></p>
                        <p id="address" class="m-0"><?= $customerDetails["customerAddress"] ?></p>
                    </div>
                    <div class="text-start my-1 psiItem">
                        <p class="label m-0 font-weight-bold"><b>City/State/Zip</b></p>
                        <p id="cityState" class="m-0"><?= $customerDetails["customerCity"] ?>, <?= $customerDetails["customerState"] ?> <?= $customerDetails["customerZipCode"] ?></p>
                    </div>
                    <div class="text-start my-1 psiItem">
                        <p class="label m-0 font-weight-bold"><b>Phone</b></p>
                        <p id="phone" class="m-0"><?= $customerDetails["customerPhone"] ?></p>
                    </div>
                    <div class="text-start my-1 psiItem">
                        <p class="label m-0 font-weight-bold"><b>Flag</b></p>
                        <p id="flag" class="m-0"><?= $vesselDetails["vesselFlag"] ?></p>
                    </div>
                    <div class="text-start my-1 psiItem">
                        <p class="label m-0 font-weight-bold"><b>Class Society</b></p>
                        <p id="classSociety" class="m-0"><?= $vesselDetails["classSociety"] ?></p>
                    </div>
                </div>
                <div class="column psiColumn">
                    <div class="text-start my-1 psiItem">
                        <p class="label m-0 font-weight-bold"><b>Service Date</b></p>
                        <p id="serviceDate" class="m-0"><?= $psiSection["serviceDate"]; ?></p>
                    </div>
                    <div class="text-start my-1 psiItem">
                        <p class="label m-0 font-weight-bold"><b>Last Inspection Date</b></p>
                        <p id="lastInspection" class="m-0"><?= $vesselDetails["lastInspection"] ?></p>
                    </div>
                    <div class="text-start my-1 psiItem">
                        <p class="label m-0 font-weight-bold"><b>Manufacturer</b></p>
                        <p id="manufacturer" class="m-0"><?= $vesselManufactureDetails["vesselManufacturer"] ?></p>
                    </div>
                    <div class="text-start my-1 psiItem">
                        <p class="label m-0 font-weight-bold"><b>Model</b></p>
                        <p id="model" class="m-0"><?= $vesselDetails["vesselModel"] ?></p>
                    </div>
                    <div class="text-start my-1 psiItem">
                        <p class="label m-0 font-weight-bold"><b>IMO #</b></p>
                        <p id="imoNum" class="m-0"><?= $vesselDetails["imoNum"] ?></p>
                    </div>
                    <div class="text-start my-1 psiItem">
                        <p class="label m-0 font-weight-bold"><b>Call Sign</b></p>
                        <p id="callSign" class="m-0"><?= $vesselDetails["callSign"] ?></p>
                    </div>
                </div>
                <div class="column psiColumn">
                    <div class="text-start my-1 psiItem">
                        <p class="label m-0 font-weight-bold"><b>Next Inspection Date</b></p>
                        <p id="nextInspection" class="m-0"><?= $vesselDetails["nextInspection"] ?></p>
                    </div>
                    <div class="text-start my-1 psiItem">
                        <p class="label m-0 font-weight-bold"><b>Approval #</b></p>
                        <p class="m-0"><?= $psiSection["appovalNum"] ?></p>
                    </div>
                    <div class="text-start my-1 psiItem">
                        <p class="label m-0 font-weight-bold"><b>Serial #</b></p>
                        <p id="serialNum" class="m-0"><?= $vesselDetails["vesselID"] ?></p>
                    </div>
                    <div class="text-start my-1 psiItem">
                        <p class="label m-0 font-weight-bold"><b>Capacity</b></p>
                        <p id="capacity" class="m-0"><?= $vesselManufactureDetails["vesselCapacity"] ?> people</p>
                    </div>
                    <div class="text-start my-1 psiItem">
                        <p class="label m-0 font-weight-bold"><b>Date of Manufacture</b></p>
                        <p id="manufactureDate" class="m-0"><?= $vesselDetails["dateOfMfr"] ?></p>
                    </div>
                    <div class="text-start my-1 psiItem">
                        <p class="label m-0 font-weight-bold"><b>Equipment Type</b></p>
                        <p id="equipType" class="m-0">Life Raft</p>
                    </div>
                </div>
            </div>
            <div id="middleSection">
                <div id="topTable">
                    <table id="pressureTests" class="text-center mt-3 col-12">
                        <thead>
                            <tr>
                                <th class="border border-dark border-1 p-0 col-1" rowspan="2"><b>TEST</b></th>
                                <th class="border border-dark border-1 p-0 col-1" rowspan="2"><b>OP. Press PSI</b></th>
                                <th class="border border-dark border-1 p-0 col-2" colspan="2"><b>Relif Valve</b></th>
                                <th class="border border-dark border-1 p-0 col-1" colspan="2"><b>Time</b></th>
                                <th class="border border-dark border-1 p-0 col-1" colspan="2"><b>Pressure</b></th>
                                <th class="border border-dark border-1 p-0 col-1" colspan="2"><b>Temperature</b></th>
                                <th class="border border-dark border-1 p-0 col-1" colspan="2"><b>Barometer</b></th>
                                <th class="border border-dark border-1 p-0 col-1" rowspan="2"><b>Finish Pressuer</b></th>
                                <th class="border border-dark border-1 p-0 col-1" rowspan="2"><b>Corrected Pressure</b></th>
                                <th class="border border-dark border-1 p-0 col-1" rowspan="2"><b>PASS/FAIL</b></th>
                            </tr>
                            <tr>
                                <th class="border border-dark border-1 p-0 col-1"><span><b>OPEN</b></span></th>
                                <th class="border border-dark border-1 p-0 col-1"><span><b>RESEAT</b></span></th>
                                <th class="border border-dark border-1 p-0"><span><b>ON</b></span></th>
                                <th class="border border-dark border-1 p-0"><span><b>OFF</b></span></th>
                                <th class="border border-dark border-1 p-0"><span><b>ON</b></span></th>
                                <th class="border border-dark border-1 p-0"><span><b>OFF</b></span></th>
                                <th class="border border-dark border-1 p-0"><span><b>ON</b></span></th>
                                <th class="border border-dark border-1 p-0"><span><b>OFF</b></span></th>
                                <th class="border border-dark border-1 p-0"><span><b>ON</b></span></th>
                                <th class="border border-dark border-1 p-0"><span><b>OFF</b></span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-dark border-1 p-0"><b>UPPER RAIL</b></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["URPSI"] != null): ?><?= $psiSection["URPSI"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span class="p-auto"><?php if($psiSection["URReliefOpen"] != null): ?><?= $psiSection["URReliefOpen"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span class="p-auto"><?php if($psiSection["URReliefReseat"] != null): ?><?= $psiSection["URReliefReseat"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["URTimeOn"] != null): ?><?= $psiSection["URTimeOn"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["URTimeOff"] != null): ?><?= $psiSection["URTimeOff"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["URPressureOn"] != null): ?><?= $psiSection["URPressureOn"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["URPressureOff"] != null): ?><?= $psiSection["URPressureOff"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["URTemperatureOn"] != null): ?><?= $psiSection["URTemperatureOn"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["URTemperatureOff"] != null): ?><?= $psiSection["URTemperatureOff"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["URBarometerOn"] != null): ?><?= $psiSection["URBarometerOn"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["URBarometerOff"] != null): ?><?= $psiSection["URBarometerOff"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["URFinishPressure"] != null): ?><?= $psiSection["URFinishPressure"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["URCorrectedPressure"] != null): ?><?= $psiSection["URCorrectedPressure"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["URPassFail"] != null): ?><?= strtoupper($psiSection["URPassFail"]) ?><?php else: ?> N/A <?php endif; ?></span></td>
                            </tr>
                            <tr>
                                <td class="border border-dark border-1 p-0"><b>LOWER RAIL</b></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["LRPSI"] != null): ?><?= $psiSection["LRPSI"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["LRReliefOpen"] != null): ?><?= $psiSection["LRReliefOpen"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["LRReliefReseat"] != null): ?><?= $psiSection["LRReliefReseat"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["LRTimeOn"] != null): ?><?= $psiSection["LRTimeOn"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["LRTimeOff"] != null): ?><?= $psiSection["LRTimeOff"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["LRPressureOn"] != null): ?><?= $psiSection["LRPressureOn"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["LRPressureOff"] != null): ?><?= $psiSection["LRPressureOff"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["LRTemperatureOn"] != null): ?><?= $psiSection["LRTemperatureOn"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["LRTemperatureOff"] != null): ?><?= $psiSection["LRTemperatureOff"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["LRBarometerOn"] != null): ?><?= $psiSection["LRBarometerOn"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["LRBarometerOff"] != null): ?><?= $psiSection["LRBarometerOff"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["LRFinishPressure"] != null): ?><?= $psiSection["LRFinishPressure"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["LRCorrectedPressure"] != null): ?><?= $psiSection["LRCorrectedPressure"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["LRPassFail"] != null): ?><?= strtoupper($psiSection["LRPassFail"]) ?><?php else: ?> N/A <?php endif; ?></span></td>
                            </tr>
                            <tr>
                                <td class="border border-dark border-1 p-0"><b>FLOOR</b></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["FLPSI"] != null): ?><?= $psiSection["FLPSI"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["FLReliefOpen"] != null): ?><?= $psiSection["FLReliefOpen"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["FLReliefReseat"] != null): ?><?= $psiSection["FLReliefReseat"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["FLTimeOn"] != null): ?><?= $psiSection["FLTimeOn"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["FLTimeOff"] != null): ?><?= $psiSection["FLTimeOff"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["FLPressureOn"] != null): ?><?= $psiSection["FLPressureOn"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["FLPressureOff"] != null): ?><?= $psiSection["FLPressureOff"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["FLTemperatureOn"] != null): ?><?= $psiSection["FLTemperatureOn"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["FLTemperatureOff"] != null): ?><?= $psiSection["FLTemperatureOff"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["FLBarometerOn"] != null): ?><?= $psiSection["FLBarometerOn"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["FLBarometerOff"] != null): ?><?= $psiSection["FLBarometerOff"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["FLFinishPressure"] != null): ?><?= $psiSection["FLFinishPressure"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["FLCorrectedPressure"] != null): ?><?= $psiSection["FLCorrectedPressure"] ?><?php else: ?> N/A <?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0"><span><?php if($psiSection["FLPassFail"] != null): ?><?= strtoupper($psiSection["FLPassFail"]) ?><?php else: ?> N/A <?php endif; ?></span></td>
                            </tr>
                            <tr>
                                <td class="text-start border border-dark border-1 px-1 py-0" colspan="4"><b>5 year Inflation Test</b></td>
                                <td class="border border-dark border-1 p-0" colspan="3"><?= $psiSection["FiveYearInflation"]; ?></td>
                                <td class="text-start border border-dark border-1 px-1 py-0" colspan="4"><b>Cylinder Serial #s</b></td>
                                <td class="border border-dark border-1 p-0" colspan="2"><span><?php if($psiSection["CylinderSerialA"] != ""): ?><?= $psiSection["CylinderSerialA"] ?><?php else: ?>N/A<?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0" colspan="2"><span><?php if($psiSection["CylinderSerialB"] != ""): ?><?= $psiSection["CylinderSerialB"] ?><?php else: ?>N/A<?php endif; ?></span></td>
                            </tr>
                            <tr>
                                <td class="text-start border border-dark border-1 px-1 py-0" colspan="4"><b>Floor Strength Test</b></td>
                                <td class="border border-dark border-1 p-0" colspan="3"><?= $psiSection["FloorStrength"]; ?></td>
                                <td class="text-start border border-dark border-1 px-1 py-0" colspan="4"><b>Weight CO2 (lbs)</b></td>
                                <td class="border border-dark border-1 p-0" colspan="2"><span><?php if($psiSection["WeightCO2A"] != ""): ?><?= $psiSection["WeightCO2A"] ?><?php else: ?>N/A<?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0" colspan="2"><span><?php if($psiSection["WeightCO2B"] != ""): ?><?= $psiSection["WeightCO2B"] ?><?php else: ?>N/A<?php endif; ?></span></td>
                            </tr>
                            <tr>
                                <td class="text-start border border-dark border-1 px-1 py-0" colspan="4"><b>NAP (Overpressure Test)</b></td>
                                <td class="border border-dark border-1 p-0" colspan="3"><?= $psiSection["NAP"]; ?></td>
                                <td class="text-start border border-dark border-1 px-1 py-0" colspan="4"><b>Weight N2 (lbs)</b></td>
                                <td class="border border-dark border-1 p-0" colspan="2"><?php if($psiSection["WeightN2A"] != ""): ?><?= $psiSection["WeightN2A"] ?><?php else: ?>N/A<?php endif; ?><span></span></td>
                                <td class="border border-dark border-1 p-0" colspan="2"><?php if($psiSection["WeightN2B"] != ""): ?><?= $psiSection["WeightN2B"] ?><?php else: ?>N/A<?php endif; ?><span></span></td>
                            </tr>
                            <tr>
                                <td class="text-start border border-dark border-1 px-1 py-0" colspan="4"><b>D/L Release Hook Lanyard Drag Test</b></td>
                                <td class="border border-dark border-1 p-0" colspan="3"><?= $psiSection["ReleaseHook"]; ?></td>
                                <td class="text-start border border-dark border-1 px-1 py-0" colspan="4"><b>Cylinder Gross Weight</b></td>
                                <td class="border border-dark border-1 p-0" colspan="2"><span><?php if($psiSection["GrossWeightA"] != ""): ?><?= $psiSection["GrossWeightA"] ?><?php else: ?>N/A<?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0" colspan="2"><span><?php if($psiSection["GrossWeightB"] != ""): ?><?= $psiSection["GrossWeightB"] ?><?php else: ?>N/A<?php endif; ?></span></td>
                            </tr>
                            <tr>
                                <td class="text-start border border-dark border-1 px-1 py-0" colspan="4"><b>D/L Load Test</b></td>
                                <td class="border border-dark border-1 p-0" colspan="3"><?= $psiSection["LoadTest"]; ?></td>
                                <td class="text-start border border-dark border-1 px-1 py-0" colspan="4"><b>Hydro Test Due Date(s)</b></td>
                                <td class="border border-dark border-1 p-0" colspan="2"><span><?php if($psiSection["HydroTestDueDateA"] != ""): ?><?= $psiSection["HydroTestDueDateA"] ?><?php else: ?>N/A<?php endif; ?></span></td>
                                <td class="border border-dark border-1 p-0" colspan="2"><span><?php if($psiSection["HydroTestDueDateB"] != ""): ?><?= $psiSection["HydroTestDueDateB"] ?><?php else: ?>N/A<?php endif; ?></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="botSection" class="d-flex justify-content-around mt-3 col-12">
                <div class="m-0 mx-2 textSmall col-5">
                    <table class="col-12">
                        <!-- <thead>
                            <tr>
                                <th class="border border-dark border-2"><b>LIFERAFT COMPONENTS</b></th>
                                <th class="border border-dark border-2"><b>INSPECTED</b></th>
                                <th class="border border-dark border-2"><b>REPAIRED/REPLACED</b></th>
                            </tr>
                        </thead> -->
                        <tbody id="componentTable">
                            <tr class="text-center h6">
                                <th class="border border-dark border-1 px-2"><b>LIFERAFT COMPONENTS</b></th>
                                <th class="border border-dark border-1 px-2"><b>INSPECTED</b></th>
                                <th class="border border-dark border-1 px-2"><b>REPAIRED/<br>REPLACED</b></th>
                            </tr>
                            <?php foreach($partsChecklist as $fieldName=>$value): ?>
                                <?php if(str_contains($fieldName, "name")){ ?>
                                    <tr>
                                        <td class="border border-dark border-1 p-1"><span><b><?php echo($value)?></b></span></td>
                                        <td class="col-2 text-center border border-dark border-1 p-1">
                                            <span class="inspectedResult"></span>
                                            <input type="hidden" class="componentsInput" name="<?= substr($fieldName, 4) ?>" value="<?= substr($fieldName, 4) ?>">
                                        </td>
                                        <td class="border border-dark border-1 text-center p-1">
                                            <span class="componentsField"></span>
                                        </td>
                                    </tr>
                            <!--End foreach-->
                            <?php } endforeach ?>
                        </tbody>
                    </table>
                </div>

                <div class="m-0 mx-2 textSmall col-5">
                    <table class="col-12">
                        <!-- <thead>
                            <tr>
                                <th class="border border-dark border-2"><b>Survival Equipment</b></th>
                                <th class="border border-dark border-2"><b>Quantity</b></th>
                                <th class="border border-dark border-2"><b>Quantity REPLACED</b></th>
                            </tr>
                        </thead> -->
                        <tbody id="survivalTable">
                            <tr class="text-center h6">
                                <th class="border border-dark border-1"><b>Survival Equipment</b></th>
                                <th class="border border-dark border-1"><b>Quantity</b></th>
                                <th class="border border-dark border-1"><b>Quantity REPLACED</b></th>
                            </tr>
                            <?php foreach($partsChecklist as $fieldName=>$value): ?>
                                <?php if(str_contains($fieldName, "label")){ ?>
                                <tr>
                                    <td class="border border-dark border-1 p-1"><span><b><?php echo($value)?></b></span></td>
                                    <td class="text-center border border-dark border-1 p-1">
                                        <span class="survivalQuantity"></span>
                                        <input type="hidden" class="survivalInput" name="<?= substr($fieldName, 5) ?>" value="<?= substr($fieldName, 4) ?>">
                                    </td>
                                    <td class="border border-dark border-1 text-center p-1">
                                        <span class="survivalReplaced"></span>
                                    </td>
                                </tr>
                            <!--End foreach-->
                            <?php } endforeach ?>
                        </tbody>
                    </table>
                    <br>
                    <table class="col-12">
                        <!-- <thead>
                            <tr>
                                <th class="border border-dark border-2"><b>Dated Items</b></th>
                                <th class="border border-dark border-2"><b>MFG Date</b></th>
                                <th class="border border-dark border-2"><b>Expiry Date</b></th>
                                <th class="border border-dark border-2"><b>Total Quantity</b></th>
                                <th class="border border-dark border-2"><b>Quantity Replaced</b></th>
                            </tr>
                        </thead> -->
                        <tbody id="datedItemsTable">
                            <tr class="text-center h6">
                                <th class="border border-dark border-1"><b>Dated Items</b></th>
                                <th class="border border-dark border-1"><b>MFG Date</b></th>
                                <th class="border border-dark border-1"><b>Expiry Date</b></th>
                                <th class="border border-dark border-1"><b>Total Quantity</b></th>
                                <th class="border border-dark border-1"><b>Quantity Replaced</b></th>
                            </tr>
                            <?php foreach($partsChecklist as $fieldName=>$value): ?>
                                <?php if(str_contains($fieldName, "dated")){ ?>
                                <tr>
                                    <td class="border border-dark border-1"><span><b><?php echo($value)?></b></span></td>
                                    <td class="border border-dark border-1">
                                        <span class="datedMFG"></span>
                                        <input type="hidden" class="datedInput" name="<?= substr($fieldName, 5) ?>">
                                    </td>
                                    <td class="border border-dark border-1">
                                        <span class="datedExpiry"></span>
                                    </td>
                                    <td class="border border-dark border-1">
                                        <span class="datedQuantity"></span>
                                    </td>
                                    <td class="border border-dark border-1">
                                        <span class="datedReplaced"></span>
                                    </td>
                                </tr>
                            <!--End foreach-->
                            <?php } endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

        <p class="text-center mt-3">This is to certify that the inflatable Liferaft detailed above has been serviced as per factory specification and by an appoved service station in strict accordance with all directives of the manufacturer.</p>
        <p class="text-center">This certificate is valid for Twelve Mounths from date of issue.</p>

        <div class="text-center">
            <span for="comment" class="text-start text-wrap text-break col-10"><b>Comments:</b> <u><?= $psiSection["Comments"]; ?></u></span>
        </div>

        <button id="printButton" class="col-1" hidden>Print</button>
    </div>
</body>
</html>
<script>
    // query selectors
    var body = document.querySelector(`body`);
    var backButton = document.querySelector(`#backButton`);
    var printButton = document.querySelector(`#printButton`);
    var woNum = <?= json_encode($woNum); ?>;
    var allInputs = document.querySelectorAll(`input`);
    var componentsInput = document.querySelectorAll(`.componentsInput`);
    var survivalInput = document.querySelectorAll(`.survivalInput`);
    var survivalQuantity = document.querySelectorAll(`.survivalQuantity`);
    var survivalReplaced = document.querySelectorAll(`.survivalReplaced`);

    var datedInput = document.querySelectorAll(`.datedInput`);
    var datedMFG = document.querySelectorAll(`.datedMFG`);
    var datedExpiry = document.querySelectorAll(`.datedExpiry`);
    var datedQuantity = document.querySelectorAll(`.datedQuantity`);
    var datedReplaced = document.querySelectorAll(`.datedReplaced`);
    var componentFieldInputs = document.querySelectorAll(`.componentsField`);
    var inspectedResult = document.querySelectorAll(`.inspectedResult`);
    var componentTable = document.querySelector(`#componentTable`);
    var survivalTable = document.querySelector(`#survivalTable`);
    var datedItemsTable = document.querySelector(`#datedItemsTable`);
    var form = document.querySelector(`form`);
    var partsTable = document.querySelector(`#botSection`);
    var selectBoxes = document.querySelectorAll(`select`);
    var Comments = document.querySelector(`#comment`);

    function fillPartsChecklist(){
        e.preventDefault();
        fillComponentChecklist();
        fillSurvivalChecklist();
        fillDatedItemsChecklist();
        disableInputs();
        while(document.readyState != "complete"){

        }
        window.print();
        window.location = 'viewWorkOrder.php?woNumber=<?= $_GET["woNumber"]; ?>'; //After dialogue box is closed, return to previous page since this one is ugly. 
    }

    printButton.addEventListener('click', (e) => {
        e.preventDefault();
        window.print();
    })

    function disableInputs(){ //All inputs should be disabled just in case redirecting fails. This page is read only. 
        let allInputs = document.querySelectorAll('input');
        for(let i = 0; i < allInputs.length; i++){
            allInputs[i].disabled = true;
        }
        for(let i = 0; i < selectBoxes.length; i++){
            selectBoxes[i].disabled = true;
        }
    }

    //Usual Fill inputs section
    function fillComponentChecklist(){
        let sheetExists = <?= json_encode($sheetExists); ?>;
        let isPCList = <?= json_encode($isPCList); ?>;

        if(!sheetExists){
            return;
        }
        
        if(isPCList)
            fillComponentPC();
        else
            fillComponentINS();
    }

    function fillComponentPC(){
        let compObj = <?= json_encode($componentValues); ?>;
        let parsedValues = [];

        for(let i = 0; i < componentTable.rows.length; i++){
            Object.keys(compObj[0]).forEach(key => {
                //parse here
                if(key != "sheetID" && key != "woNum")
                    parsedValues = compObj[0][key].split(",");
                    
                if(key == componentTable.rows[i].cells[2].childNodes[1].name){
                    componentTable.rows[i].cells[3].childNodes[1].value = parsedValues[1];
                    for(let n = 0; n < componentTable.rows[i].cells[2].childNodes.length; n++){
                        if(componentTable.rows[i].cells[2].childNodes[n].value == parsedValues[0])
                        componentTable.rows[i].cells[2].childNodes[n].checked = true;
                    }
                }
            })
        }  
    }

    function fillComponentINS(){
        console.log("filling INS")
        let compObj = <?= json_encode($componentValues); ?>;
        let parsedValues = [];

        for(let i = 0; i < componentTable.rows.length; i++){
            Object.keys(compObj[0]).forEach(key => {
                //parse here
                if(key != "sheetID" && key != "woNum")
                    parsedValues = compObj[0][key].split(",");

                if(key == componentsInput[i].name){
                    componentFieldInputs[i].innerHTML = parsedValues[1];
                    if(parsedValues[2] == "1")
                        componentTable.rows[i].cells[0].childNodes[0].checked = true;
                    if(parsedValues[0] == 1){
                        inspectedResult[i].innerHTML = "Yes";
                    }
                    else if(parsedValues[0] == 2){
                        inspectedResult[i].innerHTML = "N/A";
                    }
                    else{
                        inspectedResult[i].innerHTML = "   ";
                    }
                    // for(let n = 0; n < componentTable.rows[i].cells[2].childNodes.length; n++){
                    //     if(componentTable.rows[i].cells[2].childNodes[n].value == parsedValues[0])
                    //     inspectedResult[i].innerHTML componentTable.rows[i].cells[2].childNodes[n].checked = true;
                    // }
                }
            })
        }  
    }

    function fillSurvivalChecklist(){
        let sheetExists = <?= json_encode($sheetExists); ?>;
        let isPCList = <?= json_encode($isPCList); ?>;
        if(!sheetExists){
            return;
        }

        if(isPCList)
            fillSurvivalPC();
        else
            fillSurvivalINS();
       
    }

    function fillSurvivalPC(){
        let compObj = <?= json_encode($survivalValues); ?>;
        let parsedValues = [];

        for(let i = 0; i < survivalTable.rows.length; i++){
            Object.keys(compObj[0]).forEach(key => {
                //parse here
                if(key != "sheetID" && key != "woNum")
                    parsedValues = compObj[0][key].split(",");
                    
                if(key == survivalTable.rows[i].cells[2].childNodes[1].name){
                    survivalTable.rows[i].cells[2].childNodes[1].value = parsedValues[0];
                    survivalTable.rows[i].cells[3].childNodes[1].value = parsedValues[1];
                }
            })
        }
    }

    function fillSurvivalINS(){
        let compObj = <?= json_encode($survivalValues); ?>;
        let parsedValues = [];

        for(let i = 0; i < survivalTable.rows.length; i++){
            Object.keys(compObj[0]).forEach(key => {
                //parse here
                if(key != "sheetID" && key != "woNum")
                    parsedValues = compObj[0][key].split(",");
                    
                if(key == survivalInput[i].name){
                    survivalQuantity[i].innerHTML = parsedValues[0];
                    survivalReplaced[i].innerHTML = parsedValues[1];
                }
            })
        }
    }

    function fillDatedItemsChecklist(){
        let sheetExists = <?= json_encode($sheetExists); ?>;
        let isPCList = <?= json_encode($isPCList); ?>;
        if(!sheetExists){
            return;
        }
        if(isPCList)
            fillDatedPC();
        else
            fillDatedINS()
      
    }

    function fillDatedPC(){
        let compObj = <?= json_encode($datedItemsValues); ?>;
        let parsedValues = [];

        for(let i = 0; i < datedItemsTable.rows.length; i++){
            Object.keys(compObj[0]).forEach(key => {
                //parse here
                if(key != "sheetID" && key != "woNum")
                    parsedValues = compObj[0][key].split(",");
                    
                if(key == datedItemsTable.rows[i].cells[2].childNodes[1].name){
                    datedItemsTable.rows[i].cells[2].childNodes[1].value = parsedValues[0];
                    datedItemsTable.rows[i].cells[3].childNodes[1].value = parsedValues[1];
                    datedItemsTable.rows[i].cells[4].childNodes[1].value = parsedValues[2];
                    datedItemsTable.rows[i].cells[5].childNodes[1].value = parsedValues[3];
                }
            })
        }  
    }

    function fillDatedINS(){
        let compObj = <?= json_encode($datedItemsValues); ?>;
        let parsedValues = [];

        for(let i = 0; i < datedItemsTable.rows.length; i++){
            Object.keys(compObj[0]).forEach(key => {
                //parse here
                if(key != "sheetID" && key != "woNum")
                    parsedValues = compObj[0][key].split(",");

                if(parsedValues[4] == "1")
                    datedItemsTable.rows[i].cells[0].childNodes[0].checked = true;
                    
                if(key == datedInput[i].name){
                    datedMFG[i].innerHTML = parsedValues[0];
                    datedExpiry[i].innerHTML = parsedValues[1];
                    datedQuantity[i].innerHTML = parsedValues[2];
                    datedReplaced[i].innerHTML = parsedValues[3];
                }
            })
        }  
    }

    // backButton.addEventListener(`click`, (e) => {
    //     window.location = 'viewWorkOrder.php?woNumber=<?= $_GET["woNumber"]; ?>';
    // })

    window.print();

    setTimeout(function(){
        window.location = 'viewWorkOrder.php?woNumber=<?= $_GET["woNumber"]; ?>'
    }, 2000);

</script>