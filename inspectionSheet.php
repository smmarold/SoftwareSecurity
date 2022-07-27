<?php
    include('includes/header.php');
    include('model/temp_model.php');
    include('model/model_inspection_sheet.php');
    include('includes/functions.php');
    include('model/model_change_log.php');

    date_default_timezone_set('America/New_York');

    $partsChecklist = new PartsChecklist; //Initialize parts class
    $sheetExists = false; //If sheet doesn't already exist, create it
    $isPCList = true; //If sheet doesn't exist, fill table based on Parts list instead. 
    $feedback = "";

    if(!isset($_GET["woNumber"])){ //Return home if no wo num was passed in URL
        header("Location: home.php");
    }
//Post Section ************************************************************************************************************************************
    //Page is separated in to 4 tables, plus info at top. 
    if(filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST')
    {
        $woNum = $_POST["woNumber"];

        $psiSection = getPSISection($_POST["woNumber"]); 

        if($psiSection == [])
        {
            $sheetID = "INS".$_POST["woNumber"];
            addPsiSection($_POST["woNumber"], $sheetID);
            $psiSection = getPSISection($_POST["woNumber"]);
        }
        //Get all details for customer and vessels for sheet. 
        $woDetials = getWorkOrder($_POST["woNumber"]);
        $customerDetails = getCutomer($woDetials["customerID"]);
        $vesselDetails = getVessel($woDetials["vesselID"]);
        $vesselManufactureDetails = getVesselMenufacture($vesselDetails["vesselModel"]);

        //Top Section of Sheet ********************************************************************************************************************
        $psiSection["URPSI"] = $_POST["URPSI"];
        $psiSection["serviceDate"] = $_POST["serviceDate"];
        $psiSection["appovalNum"] = $_POST["appovalNum"];
        $psiSection["URReliefOpen"] = $_POST["URReliefOpen"];
        $psiSection["URReliefReseat"] = $_POST["URReliefReseat"];
        $psiSection["URTimeOn"] = $_POST["URTimeOn"];
        $psiSection["URTimeOff"] = $_POST["URTimeOff"];
        $psiSection["URPressureOn"] = $_POST["URPressureOn"];
        $psiSection["URPressureOff"] = $_POST["URPressureOff"];
        $psiSection["URTemperatureOn"] = $_POST["URTemperatureOn"];
        $psiSection["URTemperatureOff"] = $_POST["URTemperatureOff"];
        $psiSection["URBarometerOn"] = $_POST["URBarometerOn"];
        $psiSection["URBarometerOff"] = $_POST["URBarometerOff"];
        $psiSection["URFinishPressure"] = $_POST["URFinishPressure"];
        $psiSection["URCorrectedPressure"] = $_POST["URCorrectedPressure"];
        $psiSection["URPassFail"] = $_POST["URPassFail"];
        $psiSection["LRPSI"] = $_POST["LRPSI"];
        $psiSection["LRReliefOpen"] = $_POST["LRReliefOpen"];
        $psiSection["LRReliefReseat"] = $_POST["LRReliefReseat"];
        $psiSection["LRTimeOn"] = $_POST["LRTimeOn"];
        $psiSection["LRTimeOff"] = $_POST["LRTimeOff"];
        $psiSection["LRPressureOn"] = $_POST["LRPressureOn"];
        $psiSection["LRPressureOff"] = $_POST["LRPressureOff"];
        $psiSection["LRTemperatureOn"] = $_POST["LRTemperatureOn"];
        $psiSection["LRTemperatureOff"] = $_POST["LRTemperatureOff"];
        $psiSection["LRBarometerOn"] = $_POST["LRBarometerOn"];
        $psiSection["LRBarometerOff"] = $_POST["LRBarometerOff"];
        $psiSection["LRFinishPressure"] = $_POST["LRFinishPressure"];
        $psiSection["LRCorrectedPressure"] = $_POST["LRCorrectedPressure"];
        $psiSection["LRPassFail"] = $_POST["LRPassFail"];
        $psiSection["FLPSI"] = $_POST["FLPSI"];
        $psiSection["FLReliefOpen"] = $_POST["FLReliefOpen"];
        $psiSection["FLReliefReseat"] = $_POST["FLReliefReseat"];
        $psiSection["FLTimeOn"] = $_POST["FLTimeOn"];
        $psiSection["FLTimeOff"] = $_POST["FLTimeOff"];
        $psiSection["FLPressureOn"] = $_POST["FLPressureOn"];
        $psiSection["FLPressureOff"] = $_POST["FLPressureOff"];
        $psiSection["FLTemperatureOn"] = $_POST["FLTemperatureOn"];
        $psiSection["FLTemperatureOff"] = $_POST["FLTemperatureOff"];
        $psiSection["FLBarometerOn"] = $_POST["FLBarometerOn"];
        $psiSection["FLBarometerOff"] = $_POST["FLBarometerOff"];
        $psiSection["FLFinishPressure"] = $_POST["FLFinishPressure"];
        $psiSection["FLCorrectedPressure"] = $_POST["FLCorrectedPressure"];
        $psiSection["FLPassFail"] = $_POST["FLPassFail"];
        $psiSection["FiveYearInflation"] = $_POST["FiveYearInflation"];
        $psiSection["FloorStrength"] = $_POST["FloorStrength"];
        $psiSection["NAP"] = $_POST["NAP"];
        $psiSection["ReleaseHook"] = $_POST["ReleaseHook"];
        $psiSection["LoadTest"] = $_POST["LoadTest"];
        $psiSection["CylinderSerialA"] = $_POST["CylinderSerialA"];
        $psiSection["CylinderSerialB"] = $_POST["CylinderSerialB"];
        $psiSection["GrossWeightA"] = $_POST["GrossWeightA"];
        $psiSection["GrossWeightB"] = $_POST["GrossWeightB"];
        $psiSection["WeightCO2A"] = $_POST["WeightCO2A"];
        $psiSection["WeightCO2B"] = $_POST["WeightCO2B"];
        $psiSection["WeightN2A"] = $_POST["WeightN2A"];
        $psiSection["WeightN2B"] = $_POST["WeightN2B"];
        $psiSection["HydroTestDueDateA"] = $_POST["HydroTestDueDateA"];
        $psiSection["HydroTestDueDateB"] = $_POST["HydroTestDueDateB"];
        $psiSection["Comments"] = $_POST["Comments"];

        $psiResult = updatePsiSection($psiSection);
        // End psi section ***************************************************************************************************************************

        // Component Section of Sheet ****************************************************************************************************************
        $partsChecklist->sheetID = $_POST["sheetID"];
        $partsChecklist->woNum = $_POST["woNumber"];
        $partsChecklist->boardingLadder = $_POST["boardingLadder"];
        $partsChecklist->boardingRamp = $_POST["boardingRamp"];
        $partsChecklist->reflectiveTape = $_POST["reflectiveTape"];
        $partsChecklist->seaAnchor = $_POST["seaAnchor"];
        $partsChecklist->innerOuterLifeLine = $_POST["innerOuterLifeLine"];
        $partsChecklist->seaLightInner = $_POST["seaLightInner"];
        $partsChecklist->seaLightOuter = $_POST["seaLightOuter"];
        $partsChecklist->floatingKnife = $_POST["floatingKnife"];
        $partsChecklist->heavingLine = $_POST["heavingLine"];
        $partsChecklist->painterAssembly = $_POST["painterAssembly"];
        $partsChecklist->rightingStraps = $_POST["rightingStraps"];
        $partsChecklist->ballastBags = $_POST["ballastBags"];
        $partsChecklist->doubleFloor = $_POST["doubleFloor"];
        $partsChecklist->canopy = $_POST["canopy"];
        $partsChecklist->rainwaterCollector = $_POST["rainwaterCollector"];
        $partsChecklist->canopySupportTube = $_POST["canopySupportTube"];
        $partsChecklist->cylinderPouch = $_POST["cylinderPouch"];
        $partsChecklist->cylinderHeadCover = $_POST["cylinderHeadCover"];
        $partsChecklist->inflationCylinder = $_POST["inflationCylinder"];
        $partsChecklist->cylinderValve = $_POST["cylinderValve"];
        $partsChecklist->cvSerialNum = $_POST["cvSerialNum"];
        $partsChecklist->cylinderPullCable = $_POST["cylinderPullCable"];
        $partsChecklist->cylinderValveAdapter = $_POST["cylinderValveAdapter"];
        $partsChecklist->cylinderHydroTest = $_POST["cylinderHydroTest"];
        $partsChecklist->cylinderRefill = $_POST["cylinderRefill"];
        $partsChecklist->inflationHose = $_POST["inflationHose"];
        $partsChecklist->inletValvePoppetAssembly = $_POST["inletValvePoppetAssembly"];
        $partsChecklist->toppingUpValves = $_POST["toppingUpValves"];
        $partsChecklist->prvValve = $_POST["prvValve"];
        $partsChecklist->prvPlugs = $_POST["prvPlugs"];
        $partsChecklist->valise = $_POST["valise"];
        $partsChecklist->vacuumBag = $_POST["vacuumBag"];
        $partsChecklist->container = $_POST["container"];
        $partsChecklist->valiseIDPlacard = $_POST["valiseIDPlacard"];
        $partsChecklist->valiseLabels = $_POST["valiseLabels"];
        $partsChecklist->containerGasket = $_POST["containerGasket"];
        $partsChecklist->containerSealTape = $_POST["containerSealTape"];
        $partsChecklist->painterPlug = $_POST["painterPlug"];
        $partsChecklist->containerBurstingStrap = $_POST["containerBurstingStrap"];
        $partsChecklist->solasID = $_POST["solasID"];
        $partsChecklist->cradle = $_POST["cradle"];
        $partsChecklist->firingHead = $_POST["firingHead"];
        $partsChecklist->firingHeadSerialNum = $_POST["firingHeadSerialNum"];
        $partsChecklist->userID_Sig1 = $_POST["userID_Sig1"];
        $partsChecklist->userID_Sig2 = $_POST["userID_Sig2"];
        $partsChecklist->userID_Sig1_Filepath = $_POST["userID_Sig1_Filepath"];
        $partsChecklist->userID_Sig2_Filepath = $_POST["userID_Sig2_Filepath"];
        // End Component Section ********************************************************************************************************************

        // Survival Section of Sheet ****************************************************************************************************************
        $partsChecklist->equipmentBag = $_POST["equipmentBag"];
        $partsChecklist->handPumpHoseAdapter = $_POST["handPumpHoseAdapter"];
        $partsChecklist->sealingPlugs = $_POST["sealingPlugs"];
        $partsChecklist->spareSeaAnchor = $_POST["spareSeaAnchor"];
        $partsChecklist->instructions = $_POST["instructions"];
        $partsChecklist->paddles = $_POST["paddles"];
        $partsChecklist->sponges = $_POST["sponges"];
        $partsChecklist->canOpener = $_POST["canOpener"];
        $partsChecklist->signalWhistles = $_POST["signalWhistles"];
        $partsChecklist->signalMirror = $_POST["signalMirror"];
        $partsChecklist->fishingKit = $_POST["fishingKit"];
        $partsChecklist->flashlight = $_POST["flashlight"];
        $partsChecklist->spareBulb = $_POST["spareBulb"];
        $partsChecklist->drinkingCup = $_POST["drinkingCup"];
        $partsChecklist->jackKnife = $_POST["jackKnife"];
        $partsChecklist->seaSickBags = $_POST["seaSickBags"];
        $partsChecklist->thermalProtectiveAids = $_POST["thermalProtectiveAids"];
        $partsChecklist->firstAidKit = $_POST["firstAidKit"];
        $partsChecklist->repairKit = $_POST["repairKit"];
        $partsChecklist->desalinator = $_POST["desalinator"];
        $partsChecklist->bailer = $_POST["bailer"];
        $partsChecklist->epirb = $_POST["epirb"];
        // End Survival Section ****************************************************************************************************************

        // Dated Items Section of Sheet ****************************************************************************************************************
        $partsChecklist->rations = $_POST["rations"];
        $partsChecklist->water = $_POST["water"];
        $partsChecklist->burnCream = $_POST["burnCream"];
        $partsChecklist->aspirin = $_POST["aspirin"];
        $partsChecklist->iodineSwabs = $_POST["iodineSwabs"];
        $partsChecklist->eyeWash = $_POST["eyeWash"];
        $partsChecklist->seasickPills = $_POST["seasickPills"];
        $partsChecklist->handFlares = $_POST["handFlares"];
        $partsChecklist->parachuteFlares = $_POST["parachuteFlares"];
        $partsChecklist->chooseSmoke = $_POST["chooseSmoke"];
        $partsChecklist->dCellBatteries = $_POST["dCellBatteries"];
        $partsChecklist->repairKitCement = $_POST["repairKitCement"];
        $partsChecklist->seaLightCells = $_POST["seaLightCells"];
        $partsChecklist->hydrostaticRelease = $_POST["hydrostaticRelease"];
        $partsChecklist->epirbBattery = $_POST["epirbBattery"];        
        // End Dated Items section ****************************************************************************************************************

        //Check if adding or updating
        if($partsChecklist->checkIfExists($partsChecklist->sheetID))
            $partsResult = $partsChecklist->UpdateFullTable();
        else
            $partsResult = $partsChecklist->AddFullTable();

        if($partsResult != "" || $psiResult == true){
            addChange($_SESSION['userID'], date("Y-m-d"), $_GET["woNumber"], "Updated inspection sheet");
            $feedback = "&#9989; Update Successful &#9989;";
        }
        //load all sections into arrays, using the sheet id just created. 
        $componentValues = $partsChecklist->getComponentsList($woNum);
        for($i = 0; $i < count($componentValues); $i++){
            if($componentValues[$i]["sheetID"] == "INS" . $woNum)
            {
                $tempComp = $componentValues[$i];
                $componentValues = [];
                $componentValues[0] = $tempComp;
                $isPCList = false;
            }
        }
        //If there is a component table, there are other tables. Fill them. 
        if(count($componentValues) > 0){
            $sheetExists = true;
            $survivalValues = $partsChecklist->getSurvivalList($componentValues[0]["sheetID"]);
            $datedItemsValues = $partsChecklist->getDatedItems($componentValues[0]["sheetID"]);
        }
        else{
            $survivalValues = [];
            $datedItemsValues = [];           
        }
        $sheetExists = true; //Checked later to make sure we get the right sheet. 
        
    } // End POST section, Begin GET **********************************************************************************************************************
    elseif(isGetRequest()){

        $psiSection = getPSISection($_GET["woNumber"]);

        if($psiSection == []) //If psi is blank, no sheet exists for this page. Create one. 
        {
            $sheetID = "INS".$_GET["woNumber"];
            addPsiSection($_GET["woNumber"], $sheetID);
            $psiSection = getPSISection($_GET["woNumber"]);
        }

        $woDetials = getWorkOrder($_GET["woNumber"]); //Detials. Nice. 
        $customerDetails = getCutomer($woDetials["customerID"]);
        $vesselDetails = getVessel($woDetials["vesselID"]);
        $vesselManufactureDetails = getVesselMenufacture($vesselDetails["vesselModel"]);
        
        //Fill component checklist. If we find an INS sheet id, set isPCList to false. 
        $woNum = $_GET["woNumber"];
        $componentValues = $partsChecklist->getComponentsList($woNum);
        for($i = 0; $i < count($componentValues); $i++){
            if($componentValues[$i]["sheetID"] == "INS" . $woNum)
            {
                $tempComp = $componentValues[$i];
                $componentValues = [];
                $componentValues[0] = $tempComp;
                $isPCList = false;
            }
            else{ //initialize signature pads if the sheet is blank. 
                $componentValues[0]["userID_Sig1"] = 0;
                $componentValues[0]["userID_Sig2"] = 0;
                $componentValues[0]["userID_Sig1_Filepath"] = '#';
                $componentValues[0]["userID_Sig2_Filepath"] = '#';
            }
        }
        //Get remaining tables
        if(count($componentValues) > 0){
            $sheetExists = true;
            $survivalValues = $partsChecklist->getSurvivalList($componentValues[0]["sheetID"]);
            $datedItemsValues = $partsChecklist->getDatedItems($componentValues[0]["sheetID"]);
        }
        else{ //Initialize signature pads and table arrays if they are empty. 
            $survivalValues = [];
            $datedItemsValues = [];
            $componentValues[0] = [];
            $componentValues[0]["userID_Sig1"] = 0;
            $componentValues[0]["userID_Sig2"] = 0;
            $componentValues[0]["userID_Sig1_Filepath"] = '#';
            $componentValues[0]["userID_Sig2_Filepath"] = '#';       
        }
    }
    
?>
<!-- Fill top section of page with Customer and Vessel info --> 
<div class="container justify-content-center h-100">
    <div class="text-white text-center d-flex justify-content-between align-items-center">
        <div class="topDivs d-flex justify-content-start align-items-start mb-auto mt-2">
            <button id="backButton" class="btn btn-info text-white font-weight-bold">< Back</button>
        </div>
        <div class="pt-3 pb-3 m-0 col-8">
            <h1 class="text-white text-center"><b>Test and Survey Report <br>and<br> Re-Inspection Certificate</b></h1>
            <div id="results" class="text-center text-white"><?= $feedback; ?></div>
        </div>
        <div class="topDivs d-flex justify-content-end align-items-start mb-auto mt-2">
            <button id="printButton" class="btn btn-secondary text-white font-weight-bold" hidden>Print</button>
        </div>
    </div>
    <div id="heading" class="text-white text-center d-flex justify-content-center align-items-center">
        <p class="mx-3" style="width: 25%">Work Order #: <u><?= $woDetials["woNum"] ?></u></p>
        <p class="mx-3" style="width: 25%">Life Raft Serial #: <u><?= $vesselDetails["vesselID"] ?></u></p>
    </div>
    <form method="post">
        <div id="topSection" class="text-white border d-flex justify-content-around">
            <div>
                <input type="hidden" id="hiddenAction" name="action" value="<?= $woDetials["vesselID"]; ?>"/>
                <input type="hidden" id="customerID" name="customerID" value="<?= $woDetials["customerID"]; ?>"/>
            </div>
            <div class="column psiColumn">
                <div class="text-start my-3 psiItem">
                    <p class="label mb-1 font-weight-bold">Customer/Owner:</p>
                    <input type="text" id="customer" class="font-weight-normal m-0 inputDisabled rounded" value="<?= $customerDetails["customerName"] ?>" disabled>
                </div>
                <div class="text-start mt-3 psiItem">
                    <p class="label mb-1 font-weight-bold" >Address:</p>
                    <input type="text" id="customer" class="font-weight-normal m-0 inputDisabled rounded" value="<?= $customerDetails["customerAddress"] ?>" disabled>
                </div>
                <div class="text-start mt-3 psiItem">
                    <p class="label mb-1 font-weight-bold">City/State/Zip:</p>
                    <input type="text" id="customer" class="font-weight-normal m-0 inputDisabled rounded" value="<?= $customerDetails["customerCity"] ?>, <?= $customerDetails["customerState"] ?> <?= $customerDetails["customerZipCode"] ?>" disabled>
                </div>
                <div class="text-start mt-3 psiItem">
                    <p class="label mb-1 font-weight-bold col-12" >Phone:</p>
                    <input type="text" id="customer" class="font-weight-normal m-0 inputDisabled rounded" value="<?= $customerDetails["customerPhone"] ?>" disabled>
                </div>
                <div class="text-start mt-3 psiItem">
                    <p class="label mb-1 font-weight-bold" >Flag:</p>
                    <input type="text" id="customer" class="font-weight-normal m-0 inputDisabled rounded" value="<?= $vesselDetails["vesselFlag"] ?>" disabled>
                </div>
                <div class="text-start my-3 psiItem">
                    <p class="label mb-1 font-weight-bold" >Class Society:</p>
                    <input type="text" id="customer" class="font-weight-normal m-0 inputDisabled rounded" value="<?= $vesselDetails["classSociety"] ?>" disabled>
                </div>
            </div>
            <div class="column psiColumn">
                <div class="text-start my-3 psiItem">
                    <p class="label mb-1 font-weight-bold" >Service Date:</p>
                    <input type="date" name="serviceDate" class="inputActive rounded" value="<?php if($psiSection["serviceDate"] == null): ?><?= date("Y-m-d"); ?><?php else: ?><?= $psiSection["serviceDate"]; ?><?php endif; ?>">
                </div>
                <div class="text-start mt-3 psiItem">
                    <p class="label mb-1 font-weight-bold" >Last Inspection Date:</p>
                    <input type="text" id="customer" class="font-weight-normal m-0 inputDisabled rounded" value="<?= $vesselDetails["lastInspection"] ?>" disabled>
                </div>
                <div class="text-start mt-3 psiItem">
                    <p class="label mb-1 font-weight-bold" >Manufacturer:</p>
                    <input type="text" id="customer" class="font-weight-normal m-0 inputDisabled rounded" value="<?= $vesselManufactureDetails["vesselManufacturer"] ?>" disabled>
                </div>
                <div class="text-start mt-3 psiItem">
                    <p class="label mb-1 font-weight-bold" >Model:</p>
                    <input type="text" id="customer" class="font-weight-normal m-0 inputDisabled rounded" value="<?= $vesselDetails["vesselModel"] ?>" disabled>
                </div>
                <div class="text-start mt-3 psiItem">
                    <p class="label mb-1 font-weight-bold" >IMO #:</p>
                    <input type="text" id="customer" class="font-weight-normal m-0 inputDisabled rounded" value="<?= $vesselDetails["imoNum"] ?>" disabled>
                </div>
                <div class="text-start my-3 psiItem">
                    <p class="label mb-1 font-weight-bold" >Call Sign:</p>
                    <input type="text" id="customer" class="font-weight-normal m-0 inputDisabled rounded" value="<?= $vesselDetails["callSign"] ?>" disabled>
                </div>
            </div>
            <div class="column psiColumn">
                <div class="text-start my-3 psiItem">
                    <p class="label mb-1 font-weight-bold" >Next Inspection Date:</p>
                    <input type="text" id="customer" class="font-weight-normal m-0 inputDisabled rounded" value="<?= $vesselDetails["nextInspection"] ?>" disabled>
                </div>
                <div class="text-start mt-3 psiItem">
                    <p class="label mb-1 font-weight-bold" >Approval #:</p>
                    <input type="text" name="appovalNum" class="inputActive rounded" value="<?= $psiSection["appovalNum"] ?>" maxlength="25">
                </div>
                <div class="text-start mt-3 psiItem">
                    <p class="label mb-1 font-weight-bold" >Serial #:</p>
                    <input type="text" id="customer" class="font-weight-normal m-0 inputDisabled rounded" value="<?= $vesselDetails["vesselID"] ?>" disabled>
                </div>
                <div class="text-start mt-3 psiItem">
                    <p class="label mb-1 font-weight-bold" >Capacity:</p>
                    <input type="text" id="customer" class="font-weight-normal m-0 inputDisabled rounded" value="<?= $vesselManufactureDetails["vesselCapacity"] ?>" disabled>
                </div>
                <div class="text-start mt-3 psiItem">
                    <p class="label mb-1 font-weight-bold" >Date of Manufacture:</p>
                    <input type="text" id="customer" class="font-weight-normal m-0 inputDisabled rounded" value="<?= $vesselDetails["dateOfMfr"] ?>" disabled>
                </div>
                <div class="text-start my-3 psiItem">
                    <p class="label mb-1 font-weight-bold" >Equipment Type:</p>
                    <input type="text" id="customer" class="font-weight-normal m-0 inputDisabled rounded" value="Life Raft" disabled>
                </div>
            </div>
        </div>
        <!-- End Top Info Section--> 
        <hr class="my-2 mx-0 text-secondary opacity-100 border border-secondary border-1">
        <!-- Build PSI Table-->
        <div id="middleSection">
            <div id="topTable">
                <table id="pressureTests" class="text-white text-center mt-3">
                    <thead>
                        <tr>
                            <th class="tableLight">TEST</th>
                            <th class="border border-top-0 border-bottom-0 border-2 tableDark">PSI</th>
                            <th class="tableLight">Relif Valve</th>
                            <th class="border border-top-0 border-bottom-0 border-2 tableDark">Time</th>
                            <th class="tableLight">Pressure</th>
                            <th class="border border-top-0 border-bottom-0 border-2 tableDark">Temperature</th>
                            <th class="tableLight">Barometer</th>
                            <th class="border border-top-0 border-bottom-0 border-2 tableDark">Finish Pressure</th>
                            <th class="tableLight">Corrected Pressure</th>
                            <th class="border border-top-0 border-bottom-0 border-end-0 border-2 tableDark">PASS/FAIL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="tableLight"></td>
                            <td class="border border-top-0 border-bottom-0 border-2 tableDark"></td>
                            <td class="tableLight">
                                <div class="d-flex justify-content-around align-items-center">
                                    <span>OPEN</span>
                                    <span>RESEAT</span>
                                </div>
                            </td>
                            <td class="border border-top-0 border-bottom-0 border-2 tableDark">
                                <div class="d-flex justify-content-around align-items-center">
                                    <span>ON</span>
                                    <span>OFF</span>
                                </div>
                            </td>
                            <td class="tableLight">
                                <div class="d-flex justify-content-around align-items-center">
                                    <span>ON</span>
                                    <span>OFF</span>
                                </div>
                            </td>
                            <td class="border border-top-0 border-bottom-0 border-2 tableDark">
                                <div class="d-flex justify-content-around align-items-center">
                                    <span>ON</span>
                                    <span>OFF</span>
                                </div>
                            </td>
                            <td class="tableLight">
                                <div class="d-flex justify-content-around align-items-center">
                                    <span>ON</span>
                                    <span>OFF</span>
                                </div>
                            </td>
                            <td class="border border-top-0 border-bottom-0 border-2 tableDark"></td>
                            <td class="tableLight"></td>
                            <td class="border border-top-0 border-bottom-0 border-end-0 border-2 tableDark"></td>
                        </tr>
                        <tr>
                            <td class="tableLight">UPPER RAIL</td>
                            <td class="p-0 border border-top-0 border-bottom-0 border-2 tableDark"><input type="number" name="URPSI" size="5" class="rounded inputActive col-10 mx-1" value="<?= $psiSection["URPSI"] ?>" min="0" max="9999"></td>
                            <td class="p-0 tableLight">
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="number" name="URReliefOpen" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["URReliefOpen"] ?>" min="0" max="9999">
                                    <input type="number" name="URReliefReseat" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["URReliefReseat"] ?>" min="0" max="9999">
                                <div>
                            </td>
                            <td class="p-0 border border-top-0 border-bottom-0 border-2 tableDark">
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="number" name="URTimeOn" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["URTimeOn"] ?>" min="0" max="9999">
                                    <input type="number" name="URTimeOff" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["URTimeOff"] ?>" min="0" max="9999">
                                </div>
                            </td>
                            <td class="p-0 tableLight">
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="number" name="URPressureOn" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["URPressureOn"] ?>" min="0" max="9999">
                                    <input type="number" name="URPressureOff" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["URPressureOff"] ?>" min="0" max="9999">
                                </div>
                            </td>
                            <td class="p-0 border border-top-0 border-bottom-0 border-2 tableDark">
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="number" name="URTemperatureOn" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["URTemperatureOn"] ?>" min="0" max="9999">
                                    <input type="number" name="URTemperatureOff" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["URTemperatureOff"] ?>" min="0" max="9999">
                                </div>
                            </td>
                            <td class="p-0 tableLight">
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="number" name="URBarometerOn" size="4" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["URBarometerOn"] ?>" min="0" max="9999">
                                    <input type="number" name="URBarometerOff" size="4" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["URBarometerOff"] ?>" min="0" max="9999">
                                </div>
                            </td>
                            <td class="p-0 border border-top-0 border-bottom-0 border-2 tableDark"><input type="number" name="URFinishPressure" size="10" class="rounded inputActive col-10" value="<?= $psiSection["URFinishPressure"] ?>" min="0" max="9999"/></td>
                            <td class="p-0 tableLight"><input type="number" name="URCorrectedPressure" size="10" class="rounded inputActive col-10" value="<?= $psiSection["URCorrectedPressure"] ?>" min="0" max="9999"/></td>
                            <td class="p-0 border border-top-0 border-bottom-0 border-end-0 border-2 tableDark">
                                <select name="URPassFail" id="URPassFail" class="bg-primary border border-secondary text-white mx-2">
                                    <option value="N/A" <?php if($psiSection["URPassFail"] == "N/A"): ?>selected<?php endif; ?>>N/A</option>
                                    <option value="PASS" <?php if($psiSection["URPassFail"] == "PASS"): ?>selected<?php endif; ?>>PASS</option>
                                    <option value="FAIL" <?php if($psiSection["URPassFail"] == "FAIL"): ?>selected<?php endif; ?>>FAIL</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="tableLight"></td>
                            <td class="border border-top-0 border-bottom-0 border-2 tableDark"></td>
                            <td class="tableLight">
                                <div class="d-flex justify-content-around align-items-center">
                                    <span>OPEN</span>
                                    <span>RESEAT</span>
                                </div>
                            </td>
                            <td class="border border-top-0 border-bottom-0 border-2 tableDark">
                                <div class="d-flex justify-content-around align-items-center">
                                    <span>ON</span>
                                    <span>OFF</span>
                                </div>
                            </td>
                            <td class="tableLight">
                                <div class="d-flex justify-content-around align-items-center">
                                    <span>ON</span>
                                    <span>OFF</span>
                                </div>
                            </td>
                            <td class="border border-top-0 border-bottom-0 border-2 tableDark">
                                <div class="d-flex justify-content-around align-items-center">
                                    <span>ON</span>
                                    <span>OFF</span>
                                </div>
                            </td>
                            <td class="tableLight">
                                <div class="d-flex justify-content-around align-items-center">
                                    <span>ON</span>
                                    <span>OFF</span>
                                </div>
                            </td>
                            <td class="border border-top-0 border-bottom-0 border-2 tableDark"></td>
                            <td class="tableLight"></td>
                            <td class="border border-top-0 border-bottom-0 border-end-0 border-2 tableDark"></td>
                        </tr>
                        <tr>
                            <td class="tableLight">LOWER RAIL</td>
                            <td class="p-0 border border-top-0 border-bottom-0 border-2 tableDark"><input type="number" name="LRPSI" size="5" class="rounded inputActive col-10 mx-1" value="<?= $psiSection["LRPSI"] ?>" min="0" max="9999"></td>
                            <td class="p-0 tableLight">
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="number" name="LRReliefOpen" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["LRReliefOpen"] ?>" min="0" max="9999">
                                    <input type="number" name="LRReliefReseat" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["LRReliefReseat"] ?>" min="0" max="9999">
                                </div>
                            </td>
                            <td class="p-0 border border-top-0 border-bottom-0 border-2 tableDark">
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="number" name="LRTimeOn" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["LRTimeOn"] ?>" min="0" max="9999">
                                    <input type="number" name="LRTimeOff" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["LRTimeOff"] ?>" min="0" max="9999">
                                </div>
                            </td>
                            <td class="p-0 tableLight">
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="number" name="LRPressureOn" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["LRPressureOn"] ?>" min="0" max="9999">
                                    <input type="number" name="LRPressureOff" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["LRPressureOff"] ?>" min="0" max="9999">
                                </div>
                            </td>
                            <td class="p-0 border border-top-0 border-bottom-0 border-2 tableDark">
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="number" name="LRTemperatureOn" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["LRTemperatureOn"] ?>" min="0" max="9999">
                                    <input type="number" name="LRTemperatureOff" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["LRTemperatureOff"] ?>" min="0" max="9999">
                                </div>
                            </td>
                            <td class="p-0 tableLight">
                            <div class="d-flex justify-content-around align-items-center">
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="number" name="LRBarometerOn" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["LRBarometerOn"] ?>" min="0" max="9999">
                                    <input type="number" name="LRBarometerOff" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["LRBarometerOff"] ?>" min="0" max="9999">
                                </div>
                            </td>
                            <td class="p-0 border border-top-0 border-bottom-0 border-2 tableDark"><input type="number" name="LRFinishPressure" size="10" class="rounded inputActive col-10 mx-1" value="<?= $psiSection["LRFinishPressure"] ?>" min="0" max="9999"/></td>
                            <td class="p-0 tableLight"><input type="number" name="LRCorrectedPressure" size="10" class="rounded inputActive col-10 mx-1" value="<?= $psiSection["LRCorrectedPressure"] ?>" min="0" max="9999"/></td>
                            <td class="p-0 border border-top-0 border-bottom-0 border-end-0 border-2 tableDark">
                                <select name="LRPassFail" id="LRPassFail" class="bg-primary border border-secondary text-white mx-2">
                                    <option value="N/A" <?php if($psiSection["LRPassFail"] == "N/A"): ?>selected<?php endif; ?>>N/A</option>
                                    <option value="PASS" <?php if($psiSection["LRPassFail"] == "PASS"): ?>selected<?php endif; ?>>PASS</option>
                                    <option value="FAIL" <?php if($psiSection["LRPassFail"] == "FAIL"): ?>selected<?php endif; ?>>FAIL</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="tableLight"></td>
                            <td class="border border-top-0 border-bottom-0 border-2 tableDark"></td>
                            <td class="tableLight">
                                <div class="d-flex justify-content-around align-items-center">
                                    <span>OPEN</span>
                                    <span>RESEAT</span>
                                </div>
                            </td>
                            <td class="border border-top-0 border-bottom-0 border-2 tableDark">
                                <div class="d-flex justify-content-around align-items-center">
                                    <span>ON</span>
                                    <span>OFF</span>
                                </div>
                            </td>
                            <td class="tableLight">
                                <div class="d-flex justify-content-around align-items-center">
                                    <span>ON</span>
                                    <span>OFF</span>
                                </div>
                            </td>
                            <td class="border border-top-0 border-bottom-0 border-2 tableDark">
                                <div class="d-flex justify-content-around align-items-center">
                                    <span>ON</span>
                                    <span>OFF</span>
                                </div>
                            </td>
                            <td class="tableLight">
                                <div class="d-flex justify-content-around align-items-center">
                                    <span>ON</span>
                                    <span>OFF</span>
                                </div>
                            </td>
                            <td class="border border-top-0 border-bottom-0 border-2 tableDark"></td>
                            <td class="tableLight"></td>
                            <td class="border border-top-0 border-bottom-0 border-end-0 border-2 tableDark"></td>
                        </tr>
                        <tr>
                            <td class="p-0 tableLight">FLOOR</td>
                            <td class="p-0 border border-top-0 border-bottom-0 border-2 tableDark"><input type="number" name="FLPSI" size="5" class="rounded inputActive col-10 mx-1" value="<?= $psiSection["FLPSI"] ?>" min="0" max="9999"></td>
                            <td class="p-0 tableLight">
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="number" name="FLReliefOpen" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["FLReliefOpen"] ?>" min="0" max="9999">
                                    <input type="number" name="FLReliefReseat" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["FLReliefReseat"] ?>" min="0" max="9999">
                                </div>
                            </td>
                            <td class="p-0 border border-top-0 border-bottom-0 border-2 tableDark">
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="number" name="FLTimeOn" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["FLTimeOn"] ?>" min="0" max="9999">
                                    <input type="number" name="FLTimeOff" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["FLTimeOff"] ?>" min="0" max="9999">
                                </div>
                            </td>
                            <td class="p-0 tableLight">
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="number" name="FLPressureOn" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["FLPressureOn"] ?>" min="0" max="9999">
                                    <input type="number" name="FLPressureOff" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["FLPressureOff"] ?>" min="0" max="9999">
                                </div>
                            </td>
                            <td class="p-0 border border-top-0 border-bottom-0 border-2 tableDark">
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="number" name="FLTemperatureOn" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["FLTemperatureOn"] ?>" min="0" max="9999">
                                    <input type="number" name="FLTemperatureOff" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["FLTemperatureOff"] ?>" min="0" max="9999">
                                </div>
                            </td>
                            <td class="p-0 tableLight">
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="number" name="FLBarometerOn" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["FLBarometerOn"] ?>" min="0" max="9999">
                                    <input type="number" name="FLBarometerOff" size="5" class="rounded inputActive col-5 mx-1" value="<?= $psiSection["FLBarometerOff"] ?>" min="0" max="9999">
                                </div>
                            </td>
                            <td class="p-0 border border-top-0 border-bottom-0 border-2 tableDark"><input type="number" name="FLFinishPressure" size="10" class="rounded inputActive col-10 mx-1" value="<?= $psiSection["FLFinishPressure"] ?>" min="0" max="9999"/></td>
                            <td class="p-0 tableLight"><input type="number" name="FLCorrectedPressure" size="10" class="rounded inputActive col-10 mx-1" value="<?= $psiSection["FLCorrectedPressure"] ?>" min="0" max="9999"/></td>
                            <td class="p-0 border border-top-0 border-bottom-0 border-end-0 border-2 tableDark">
                                <select name="FLPassFail" id="FLPassFail" class="bg-primary border border-secondary text-white mx-2">
                                    <option value="N/A" <?php if($psiSection["FLPassFail"] == "N/A"): ?>selected<?php endif; ?>>N/A</option>
                                    <option value="PASS" <?php if($psiSection["FLPassFail"] == "PASS"): ?>selected<?php endif; ?>>PASS</option>
                                    <option value="FAIL" <?php if($psiSection["FLPassFail"] == "FAIL"): ?>selected<?php endif; ?>>FAIL</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr class="my-1 mx-0 text-secondary opacity-100 border border-secondary border-1">
            <div id="botTable">
                <table id="passFail" class="table table-borderless table-striped text-white text-center align-text-center">
                    <tbody>
                        <tr>
                            <td class="text-start col-4">5 year Inflation Test</td>
                            <td>
                                <select name="FiveYearInflation" id="inflationPassFail" class="bg-primary border border-secondary text-white mx-2">
                                    <option value="N/A" <?php if($psiSection["FiveYearInflation"] == "N/A"): ?>selected<?php endif; ?>>N/A</option>
                                    <option value="PASS" <?php if($psiSection["FiveYearInflation"] == "PASS"): ?>selected<?php endif; ?>>PASS</option>
                                    <option value="FAIL" <?php if($psiSection["FiveYearInflation"] == "FAIL"): ?>selected<?php endif; ?>>FAIL</option>
                                </select>
                            </td>
                            <td class="text-start">Cylinder Serial #s</td>
                            <td><input type="text" name="CylinderSerialA" size="10" class="rounded inputActive col-8" value="<?= $psiSection["CylinderSerialA"] ?>" maxlength="25"/></td>
                            <td><input type="text" name="CylinderSerialB" size="10" class="rounded inputActive col-8" value="<?= $psiSection["CylinderSerialB"] ?>" maxlength="25"/></td>
                        </tr>
                        <tr>
                            <td class="text-start col-4">Floor Strength Test</td>
                            <td>
                                <select name="FloorStrength" id="inflationPassFail" class="bg-primary border border-secondary text-white mx-2">
                                    <option value="N/A" <?php if($psiSection["FloorStrength"] == "N/A"): ?>selected<?php endif; ?>>N/A</option>
                                    <option value="PASS" <?php if($psiSection["FloorStrength"] == "PASS"): ?>selected<?php endif; ?>>PASS</option>
                                    <option value="FAIL" <?php if($psiSection["FloorStrength"] == "FAIL"): ?>selected<?php endif; ?>>FAIL</option>
                                </select>
                            </td>
                            <td class="text-start">Weight CO2 (lbs)</td>
                            <td><input type="number" name="WeightCO2A" size="10" class="rounded inputActive col-8" value="<?= $psiSection["WeightCO2A"] ?>" min="0" max="9999"/></td>
                            <td><input type="number" name="WeightCO2B" size="10" class="rounded inputActive col-8" value="<?= $psiSection["WeightCO2B"] ?>" min="0" max="9999"/></td>
                        </tr>
                        <tr>
                            <td class="text-start col-4">NAP (Overpressure Test)</td>
                            <td>
                                <select name="NAP" id="inflationPassFail" class="bg-primary border border-secondary text-white mx-2">
                                    <option value="N/A" <?php if($psiSection["NAP"] == "N/A"): ?>selected<?php endif; ?>>N/A</option>
                                    <option value="PASS" <?php if($psiSection["NAP"] == "PASS"): ?>selected<?php endif; ?>>PASS</option>
                                    <option value="FAIL" <?php if($psiSection["NAP"] == "FAIL"): ?>selected<?php endif; ?>>FAIL</option>
                                </select>
                            </td>
                            <td class="text-start">Weight N2 (lbs)</td>
                            <td><input type="number" name="WeightN2A" size="10" class="rounded inputActive col-8" value="<?= $psiSection["WeightN2A"] ?>" min="0" max="9999"/></td>
                            <td><input type="number" name="WeightN2B" size="10" class="rounded inputActive col-8" value="<?= $psiSection["WeightN2B"] ?>" min="0" max="9999"/></td>
                        </tr>
                        <tr>
                            <td class="text-start col-4">D/L Release Hook Lanyard Drag Test</td>
                            <td>
                                <select name="ReleaseHook" id="inflationPassFail" class="bg-primary border border-secondary text-white mx-2">
                                    <option value="N/A" <?php if($psiSection["ReleaseHook"] == "N/A"): ?>selected<?php endif; ?>>N/A</option>
                                    <option value="PASS" <?php if($psiSection["ReleaseHook"] == "PASS"): ?>selected<?php endif; ?>>PASS</option>
                                    <option value="FAIL" <?php if($psiSection["ReleaseHook"] == "FAIL"): ?>selected<?php endif; ?>>FAIL</option>
                                </select>
                            </td>
                            <td class="text-start">Cylinder Gross Weight</td>
                            <td><input type="number" name="GrossWeightA" size="10" class="rounded inputActive col-8" value="<?= $psiSection["GrossWeightA"] ?>" min="0" max="9999"/></td>
                            <td><input type="number" name="GrossWeightB" size="10" class="rounded inputActive col-8" value="<?= $psiSection["GrossWeightB"] ?>" min="0" max="9999"/></td>
                        </tr>
                        <tr>
                            <td class="text-start col-4">D/L Load Test</td>
                            <td>
                                <select name="LoadTest" id="inflationPassFail" class="bg-primary border border-secondary text-white mx-2">
                                    <option value="N/A" <?php if($psiSection["LoadTest"] == "N/A"): ?>selected<?php endif; ?>>N/A</option>
                                    <option value="PASS" <?php if($psiSection["LoadTest"] == "PASS"): ?>selected<?php endif; ?>>PASS</option>
                                    <option value="FAIL" <?php if($psiSection["LoadTest"] == "FAIL"): ?>selected<?php endif; ?>>FAIL</option>
                                </select>
                            </td>
                            <td class="text-start">Hydro Test Due Date(s)</td>
                            <td><input type="date" name="HydroTestDueDateA" size="10" class="rounded inputActive col-8" value="<?= $psiSection["HydroTestDueDateA"] ?>" min="<?= date("Y-m-d"); ?>"/></td>
                            <td><input type="date" name="HydroTestDueDateB" size="10" class="rounded inputActive col-8" value="<?= $psiSection["HydroTestDueDateB"] ?>" min="<?= date("Y-m-d"); ?>"/></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- End PSI Section-->
        <hr class="my-2 mx-0 text-secondary opacity-100 border border-secondary border-1">
        <!-- Begin Component Table Section-->
        <div id="botSection" class="container">
            <table class="table table-borderless table-striped text-white text-center align-text-center">
                <thead>
                    <tr>
                        <th></th>
                        <th>LIFERAFT COMPONENTS</th>
                        <th class="border border-top-0 border-bottom-0 border-2">INSPECTED</th>
                        <th>REPAIRED/REPLACED</th>
                    </tr>
                </thead>
                <tbody id="componentTable"> <!--As with Parts Page, create the table with foreach. Item names are hardcoded in the Class.  -->
                    <?php foreach($partsChecklist as $fieldName=>$value): ?>
                        <?php if(str_contains($fieldName, "name")){ ?> 
                            <tr>
                                <td> <!-- This page also adds checkboxes in each row for tracking where the user left off on inspection-->
                                    <label class="yesRadioContainer m-0"><input type="checkbox" name="completedCheck" value="inspected" class="componentCheckboxes"><span class="yesRadio"></span></label>
                                </td>
                                <td class="text-start"><span><?php echo($value)?></span></td>
                                <td class="border border-top-0 border-bottom-0 border-2">
                                    <div class="d-flex justify-content-around">
                                        <label class="yesRadioContainer m-0">Yes<input type="radio" name="<?= substr($fieldName, 4); ?>" value="1" class="componentsRadio componentsYesRadio"><span class="yesRadio"></span></label>

                                        <label class="naRadioContainer m-0">N/A<input type="radio" name="<?= substr($fieldName, 4); ?>" value="2" class="componentsRadio componentsNaRadio"><span class="naRadio"></span></label>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" class="componentsField componentInputs col-10 rounded inputActive" name="rep<?= substr($fieldName, 4) ?>" maxlength="20">
                                </td>
                            </tr>
                    <!--End foreach-->
                    <?php } endforeach ?>
                </tbody>
            </table>
            <!-- End Component Section-->
            <hr class="m-0 text-secondary opacity-100 border border-secondary border-1">
            <!-- Begin Survival Table Section-->
            <table class="table table-borderless table-striped text-white text-center align-text-center mt-3">
                <thead>
                    <tr>
                        <th></th>
                        <th>Survival Equipment</th>
                        <th class="border border-top-0 border-bottom-0 border-2">Quantity</th>
                        <th>Quantity REPLACED</th>
                    </tr>
                </thead>
                <tbody id="survivalTable"> <!-- Structured the same as Component Table-->
                    <?php foreach($partsChecklist as $fieldName=>$value): ?>
                        <?php if(str_contains($fieldName, "label")){ ?>
                        <tr>
                            <td>
                                <label class="yesRadioContainer m-0"><input type="checkbox" name="completedCheck" value="inspected" class="survivalCheckboxes"><span class="yesRadio"></span></label>
                                <!-- <input type="checkbox" name="completedCheck" value="inspected"> -->
                            </td>
                            <td class="text-start"><span><?php echo($value)?></span></td>
                            <td class="border border-top-0 border-bottom-0 border-2">
                                <input type="number" class="survivalQTYField col-10 rounded inputActive" name="<?= substr($fieldName, 5); ?>" min="0" max="99999">
                            </td>
                            <td>
                                <input type="number" name="<?= substr($fieldName, 5) ?>QTYRep" class="col-10 rounded inputActive" min="0" max="99999">
                            </td>
                        </tr>
                    <!--End foreach-->
                    <?php } endforeach ?>
                </tbody>
            </table>
            <!-- End Survival Section-->
            <hr class="m-0 text-secondary opacity-100 border border-secondary border-1">
            <!-- Begin Dated Items Section-->
            <table class="table table-borderless table-striped text-white text-center align-text-center mt-3">
                <thead>
                    <tr>
                        <th></th>
                        <th>Dated Items</th>
                        <th class="border border-top-0 border-bottom-0 border-2">MFG Date</th>
                        <th>Expiry Date</th>
                        <th class="border border-top-0 border-bottom-0 border-2">Total Quantity</th>
                        <th>Quantity Replaced</th>
                    </tr>
                </thead>
                <tbody id="datedItemsTable"> <!-- Structured the same as Component Table-->
                    <?php foreach($partsChecklist as $fieldName=>$value): ?>
                        <?php if(str_contains($fieldName, "dated")){ ?>
                        <tr>
                            <td>
                                <label class="yesRadioContainer m-0"><input type="checkbox" name="completedCheck" value="inspected" class="datedItemsCheckboxes"><span class="yesRadio"></span></label>
                                <!-- <input type="checkbox" name="completedCheck" value="inspected"> -->
                            </td>
                            <td class="text-start"><span><?php echo($value)?></span></td>
                            <td class="border border-top-0 border-bottom-0 border-2">
                                <input type="date" name="<?= substr($fieldName, 5) ?>" class="col-10 rounded inputActive" max="<?= date("Y-m-d"); ?>">
                            </td>
                            <td>
                                <input type="date" name="<?= substr($fieldName, 5) ?>exp" class="col-10 rounded inputActive" min="<?= date("Y-m-d"); ?>">
                            </td>
                            <td class="border border-top-0 border-bottom-0 border-2">
                                <input type="number" name="<?= substr($fieldName, 5) ?>total" class="col-10 rounded inputActive" min="0" max="99999">
                            </td>
                            <td>
                                <input type="number" name="<?= substr($fieldName, 5) ?>QTYRep" class="col-10 rounded inputActive" min="0" max="99999">
                            </td>
                        </tr>
                    <!--End foreach-->
                    <?php } endforeach ?>
                </tbody>
            </table>
            <!--Signature Pad Section. Contains hidden fields for storing user ID on sign, and Base64/BLOB data. -->
            <div class="sigStuff text-white d-flex justify-content-around">
                <div id="sigDiv1">
                    <input class="inputActive" name="userID_Sig1" type="number" id="userID_Sig1" value="<?= $_SESSION['userID']; ?>" hidden/>
                    <input class="inputActive" name="userID_Sig1_Filepath" type="text" id="blobData" hidden/>
                    <canvas class="bg-white rounded" width="400" height="225"></canvas>
                    <div class="d-flex justify-content-around">
                        <button id="sigSave1" class="btn btn-secondary text-white font-weight-bold">Save Signature</button>
                        <button class="clearButtons btn btn-info text-white font-weight-bold">Clear Signature</button>
                    </div>
                    </div>
                    <img id="sigImage" src="<?= $componentValues[0]["userID_Sig1_Filepath"]; ?>" class="bg-white rounded"/>
                    <div id="sigDiv2">
                        <input class="inputActive" name="userID_Sig2" type="number" id="userID_Sig2" value="<?= $_SESSION['userID']; ?>" hidden/>
                        <input class="inputActive" name="userID_Sig2_Filepath" type="text" id="blobDataTwo" hidden/>   
                        <canvas class="bg-white rounded" width="400" height="225"></canvas>
                        <div class="d-flex justify-content-around">
                            <button id="sigSave2" class="btn btn-secondary text-white font-weight-bold">Save Signature</button>
                            <button class="clearButtons btn btn-info text-white font-weight-bold">Clear Signature</button>
                        </div>
                    </div>
                    <img id="sigImage2" src="<?= $componentValues[0]["userID_Sig2_Filepath"]; ?>" class="bg-white rounded"/>
                </div>
            </div>
            <!-- End Signature Pad Section-->
            <p class="text-white text-center">This is to certify that the inflatable Liferaft detailed above has been serviced as per factory specification and by an appoved service station in strict accordance with all directives of the manufacturer.</p>
            <p class="text-white text-center">This certificate is valid for Twelve Mounths from date of issue.</p>

            <div class="text-center">
                <textarea id="comment" name="Comments" class="bg-white rounded" rows="5" cols="100" placeholder="Comments" maxlength="500"></textarea>
            </div>

        </form>

        <div class="text-center">
            <button id="saveButton" class="btn btn-secondary text-white font-weight-bold my-3">Save</button>
        </div>
        <footer>
            <hr class="m-0 text-secondary opacity-100 border border-secondary border-1">
            <div class="p-1">
        </footer>
    </div>
</body>
</html>
<script src="scripts/sigPad.js"></script> <!-- Link to Signature Pad functionality file-->
<script>
    //Query Selector Section **********************************************************************************************************************************
    var backButton = document.querySelector(`#backButton`);
    var printButton = document.querySelector(`#printButton`);
    var saveButton = document.querySelector(`#saveButton`);
    var woNum = <?= json_encode($woNum); ?>;
    var results = document.querySelector(`#results`);
    var allInputs = document.querySelectorAll(`input`);
    var componentYesRadioInputs = document.querySelectorAll(`.componentsYesRadio`);
    var componentNaRadioInputs = document.querySelectorAll(`.componentsNaRadio`);
    var componentInputs = document.querySelectorAll(`.componentInputs`);
    var componentCheckboxes = document.querySelectorAll(`.componentCheckboxes`);
    var survivalCheckboxes = document.querySelectorAll(`.survivalCheckboxes`);
    var datedItemsCheckboxes = document.querySelectorAll(`.datedItemsCheckboxes`);
    var componentRadioInputs = document.querySelectorAll(`.componentsRadio`);
    var componentFieldInputs = document.querySelectorAll(`.componentsField`);
    var componentTable = document.querySelector(`#componentTable`);
    var survivalTable = document.querySelector(`#survivalTable`);
    var datedItemsTable = document.querySelector(`#datedItemsTable`);
    var form = document.querySelector(`form`);
    var partsTable = document.querySelector(`#botSection`);
    var Comments = document.querySelector(`#comment`);
    var userID_Sig1 = document.querySelector(`#userID_Sig1`);
    var userID_Sig2 = document.querySelector(`#userID_Sig2`);
    var sigImg = document.querySelector(`#sigImage`);
    var sigImg2 = document.querySelector(`#sigImage2`);
    var sigDiv1 = document.querySelector('#sigDiv1');
    var sigDiv2 = document.querySelector('#sigDiv2');
    var sigSave1 = document.querySelector('#sigSave1');
    var sigSave2 = document.querySelector('#sigSave2');
    var clr = document.querySelectorAll(`.clearButtons`);
    //End Query Selector Section **********************************************************************************************************************************
    
    //vars for working with Signature pads. ***********************************************************************************************************************
    var isSigned = false;
    var isSigned2 = false;
    var newSig1 = false;
    var newSig2 = false;

    //Event Listeners ***********************************************************************************************************************
    //Save buttons for sig pads. Changes appropraite booleans so we don't overwrite if no new sig was present
    sigSave1.addEventListener('click',(e)=>{
        e.preventDefault();
        isSigned = true;
        newSig1 = true;
        sigDiv1.hidden = true;
        sigImg.hidden = false;
        sigImg.src = c[0].toDataURL("image/png");
    })

    sigSave2.addEventListener('click',(e)=>{
        e.preventDefault();
        isSigned2 = true;
        newSig2 = true;
        sigDiv2.hidden = true;
        sigImg2.hidden = false;
        sigImg2.src = c[1].toDataURL("image/png");
    })
    //Redirects to cert page, which has special formatting for printing. 
    printButton.addEventListener(`click`, (e) => {
        window.location = 'inspectionCert.php?woNumber=<?= $_GET["woNumber"]; ?>';
    })

    backButton.addEventListener(`click`, (e) => {
        window.location = 'viewWorkOrder.php?woNumber=<?= $_GET["woNumber"]; ?>';
    })
    //On save, get all info and rebuild the form with out 'coded' values. 
    saveButton.addEventListener(`click`, (e) => {
        e.preventDefault();
        SaveIt();
        let submission = GetComponentFields();
        submission += GetSurvivalFields();
        submission += GetDatedItemsFields();
        submission += `<input type="text" name="Comments" value=${Comments.value}>`;

        form.hidden = true;
        results.hidden = true;
        partsTable.innerHTML = "";
        partsTable.innerHTML = submission;
        form.submit();
    })
    // Functions for getting values from the inputs and rebuilding the form. 
    function GetComponentFields(){
        let recordName = [];
        let componentsFinalValue = [];

        let comSubmission = `<input type="text" name="woNumber" value=${woNum}>`;
        comSubmission += `<input type="text" name="sheetID" value="INS${woNum}">`;

        for(let i = 0; i < componentTable.rows.length; i++){
            let radioValue = "";
            let checkboxValue = "";

            if(componentYesRadioInputs[i].checked == true){
                radioValue = "1";
            }
            else if(componentNaRadioInputs[i].checked == true){
                radioValue = "2";
            }
            else{
                radioValue = "0";
            }

            if(componentCheckboxes[i].checked == true){
                checkboxValue = "1";
            }
            else{
                checkboxValue = "0";
            }

            //nameSplit = componentTable.rows[i].cells[0].childNodes[0].innerHTML.split(' ');
            recordName[i] = componentYesRadioInputs[i].name;

            

            componentsFinalValue[i] = radioValue + "," + componentTable.rows[i].cells[3].childNodes[1].value + "," + checkboxValue;
            comSubmission += `<input type="text" name=${recordName[i]} value="${componentsFinalValue[i]}">`; 
        }

        comSubmission += `<input type="text" name=${blobInput.name} value="${blobInput.value}">`;
        comSubmission += `<input type="text" name=${blobInputTwo.name} value="${blobInputTwo.value}">`;

        if(!isSigned)
            userID_Sig1.value = "0";
        if(!isSigned2)
            userID_Sig2.value = "0";
            
        comSubmission += `<input class="inputActive" name="${userID_Sig1.name}" type="number" id="userID_Sig1" value="${userID_Sig1.value}" hidden />`;
        comSubmission += `<input class="inputActive" name="${userID_Sig2.name}" type="number" id="userID_Sig1" value="${userID_Sig2.value}" hidden />`;

        return comSubmission;
    }

    function GetSurvivalFields(){
        let recordName = [];
        let survivalFinalValue = [];
        let surSubmission = "";
        let checkboxValue = "";

        for(let i = 0; i < survivalTable.rows.length; i++){
            recordName[i] = survivalTable.rows[i].cells[2].childNodes[1].name;

            if(survivalCheckboxes[i].checked == true){
                checkboxValue = "1";
            }
            else{
                checkboxValue = "0";
            }

            survivalFinalValue[i] = survivalTable.rows[i].cells[2].childNodes[1].value + "," + survivalTable.rows[i].cells[3].childNodes[1].value + "," + checkboxValue;
            surSubmission += `<input type="text" name=${recordName[i]} value="${survivalFinalValue[i]}">`; 
        }
        return surSubmission;
    }

    function GetDatedItemsFields(){
        let recordName = [];
        let datedItemsFinalValue = [];
        let datSubmission = "";
        let checkboxValue = "";

        for(let i = 0; i < datedItemsTable.rows.length; i++){
            recordName[i] = datedItemsTable.rows[i].cells[2].childNodes[1].name;

            if(datedItemsCheckboxes[i].checked == true){
                checkboxValue = "1";
            }
            else{
                checkboxValue = "0";
            }

            datedItemsFinalValue[i] = datedItemsTable.rows[i].cells[2].childNodes[1].value + "," + datedItemsTable.rows[i].cells[3].childNodes[1].value + "," + datedItemsTable.rows[i].cells[4].childNodes[1].value + "," + datedItemsTable.rows[i].cells[5].childNodes[1].value + "," + checkboxValue;
            datSubmission += `<input type="text" name=${recordName[i]} value="${datedItemsFinalValue[i]}">`;
            console.log(recordName[i] + datedItemsFinalValue[i]); 
        }

        return datSubmission;
    }
    //End Get Functions

    //Called on page load. 
    function fillPartsChecklist(){
        fillComponentChecklist();
        fillSurvivalChecklist();
        fillDatedItemsChecklist();
        sigPadCheck();
        Comments.innerHTML = "<?= $psiSection["Comments"] ?>";
    }

    //Using the userID fields for the sig pads in the DB, we see if there is a signature. if the field 0, 
    // show the sig pad and hide the img element. 
    function sigPadCheck(){
        let sigCheck1 = '0';
        let sigCheck2 = '0'; 
        if(`<?= (string)$componentValues[0]["userID_Sig1"] ?>` != ``)
            sigCheck1 = `<?= (string)$componentValues[0]["userID_Sig1"]; ?>`;
        if(`<?= (string)$componentValues[0]["userID_Sig2"] ?>` != ``)
            sigCheck2 = `<?= (string)$componentValues[0]["userID_Sig2"]; ?>`;

        if(sigCheck1 != '0'){
            sigDiv1.hidden = true;
            sigImg.hidden = false;
            isSigned = true;
        }
        else{
            console.log("Got Here");
            sigDiv1.hidden = false;
            sigImg.hidden = true;
            isSigned = false;
        }
        if(sigCheck2 != '0'){
            sigDiv2.hidden = true;
            sigImg2.hidden = false;
            isSigned2 = true;
        }
        else{
            console.log("Got Here 2");
            sigDiv2.hidden = false;
            sigImg2.hidden = true;
            isSigned2 = false;
        }

        if(isSigned && isSigned2){
            allInputs = document.querySelectorAll('input');

            for(let i=0; i<allInputs.length;i++){
                allInputs[i].disabled = true;
                allInputs[i].classList.add("inputDisabled");
                allInputs[i].classList.remove("inputActive");
                printButton.hidden = false;
            }
        }
    }

    //Functions for getting the values from the PHP arrays. Func called depends on whether we are using a PC or INS array.  ******************************************
    function fillComponentChecklist(){
        let sheetExists = <?= json_encode($sheetExists); ?>; 
        let isPCList = <?= json_encode($isPCList); ?>;

        if(!sheetExists){ //Do nothing if sheet does not exist yet. 
            return;
        }
        
        if(isPCList)
            fillComponentPC();
        else
            fillComponentINS();
    }

    function fillComponentPC(){ //For PC filling
        let sheetExists = <?= json_encode($sheetExists); ?>;
        if(!sheetExists){
            return;
        }

        let compObj = <?= json_encode($componentValues); ?>;
        let parsedValues = [];

        for(let i = 0; i < componentTable.rows.length; i++){
            Object.keys(compObj[0]).forEach(key => {
                //parse here
                if(key != "sheetID" && key != "woNum" && key != "userID_Sig2_Filepath" && key != "userID_Sig1_Filepath" && key != "userID_Sig2" && key != "userID_Sig1")
                    parsedValues = compObj[0][key].split(",");
                
                if(key == componentYesRadioInputs[i].name){
                    componentInputs[i].value = parsedValues[1];
                    if(parsedValues[0] == 1){
                        componentYesRadioInputs[i].checked = true;
                        componentNaRadioInputs[i].checked = false;
                    }
                    else if(parsedValues[0] == 2){
                        componentNaRadioInputs[i].checked = true;
                        componentYesRadioInputs[i].checked = false;
                    }
                }
            })
        }        
    }

    function fillComponentINS(){ //For INS filling
        console.log("filling INS")
        let compObj = <?= json_encode($componentValues); ?>;
        let parsedValues = [];

        for(let i = 0; i < componentTable.rows.length; i++){
            console.log(componentTable.rows[i].cells[0].childNodes[0]);
            Object.keys(compObj[0]).forEach(key => {
                //parse here
                if(key != "sheetID" && key != "woNum" && key != "userID_Sig2_Filepath" && key != "userID_Sig1_Filepath" && key != "userID_Sig2" && key != "userID_Sig1")
                    parsedValues = compObj[0][key].split(",");
                    
                if(key == componentYesRadioInputs[i].name){
                    if(parsedValues[2] == "1")
                        componentCheckboxes[i].checked = true;
                    else
                        componentCheckboxes[i].checked = false;

                    componentInputs[i].value = parsedValues[1];
                    if(parsedValues[0] == 1){
                        componentYesRadioInputs[i].checked = true;
                        componentNaRadioInputs[i].checked = false;
                    }
                    else if(parsedValues[0] == 2){
                        componentNaRadioInputs[i].checked = true;
                        componentYesRadioInputs[i].checked = false;
                    }
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

                if(key == survivalTable.rows[i].cells[2].childNodes[1].name){
                    survivalTable.rows[i].cells[2].childNodes[1].value = parsedValues[0];
                    survivalTable.rows[i].cells[3].childNodes[1].value = parsedValues[1];
                    if(parsedValues[2] == '1')
                        survivalCheckboxes[i].checked = true;
                    else
                        survivalCheckboxes[i].checked = false;
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
                    
                if(key == datedItemsTable.rows[i].cells[2].childNodes[1].name){
                    datedItemsTable.rows[i].cells[2].childNodes[1].value = parsedValues[0];
                    datedItemsTable.rows[i].cells[3].childNodes[1].value = parsedValues[1];
                    datedItemsTable.rows[i].cells[4].childNodes[1].value = parsedValues[2];
                    datedItemsTable.rows[i].cells[5].childNodes[1].value = parsedValues[3];
                    if(parsedValues[4] == "1")
                        datedItemsCheckboxes[i].checked = true;
                    else
                        datedItemsCheckboxes[i].checked = false;
                }
            })
        }  
    }
    //END Fill Functions ********************************************************************************************************************************************

    //Begin Signature Pad Code **************************************************************************************************************************************
    //Save It will check if we ahve a new signature to save to the DB. If so, convert the sig to Base64, and store the currently logged in user ID in the relevant fields.
    function SaveIt()
    {  
        if(isSigned && newSig1){
            var image = c[0].toDataURL("image/png");
            blobInput.value = image;
        }
        else if(isSigned && !newSig1){
            blobInput.value = sigImg.src;
            userID_Sig1.value = `<?= $componentValues[0]["userID_Sig1"] ?>`
        }
        if(isSigned2 && newSig2){
            var image2 = c[1].toDataURL("image/png");
            blobInputTwo.value = image2;
        }
        else if(isSigned2 && !newSig2){
            blobInputTwo.value = sigImg2.src
            userID_Sig2.value = `<?= $componentValues[0]["userID_Sig2"] ?>`
        }
        
    }
    //If the sig pads are active, add event listeners to the clear buttons.
    if(clr[0]){
        clr[0].addEventListener(`click`, (e) => {
            e.preventDefault();
            c[0].getContext(`2d`).clearRect(0,0,c[0].width, c[0].height);
            isSigned = false;
            newSig1 = false;
        });
    }
    if(clr[1]){
        clr[1].addEventListener(`click`,  (e) => {
            e.preventDefault();
            c[1].getContext(`2d`).clearRect(0,0,c[0].width, c[0].height);
            isSigned2 = false;
            newSig2 = false;
        });
    }
    //End Signature Pad Code ************************************************************************************************************************************
</script>