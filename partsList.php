<?php
    include('includes/header.php');
    include('includes/functions.php');
    include('model/model_inspection_sheet.php');
    include('model/model_change_log.php');

    date_default_timezone_set('America/New_York');
    
    $partsChecklist = new PartsChecklist; //Instantiate parts checklist obj. Contains all relevant fields to this form. 
    $sheetExists = false; //Initialize this, change if we return a sheet on load. Checked in JavaScript when filling fields. 
    $result = "";

    if(!isset($_GET["woNumber"])){ //No URL Param? No Good. Go Home. 
        header("Location: home.php");
    }
    elseif($_SESSION["accountType"] != "Admin" && $_SESSION["accountType"] != 'Technician' && $_SESSION["accountType"] != 'Supervisor'){
        header("Location: viewWorkOrder.php?woNumber={$_GET["woNumber"]}");
    }

    //The parts list is broken into three sections, each having a table in the DB. 
    if(isPostRequest()){
        //Component Fields ********************************************************************************************************************************
        $woNum = $_POST["woNumber"];
        $partsChecklist->sheetID = $_POST["sheetID"];
        $partsChecklist->woNum = $_POST["woNumber"];
        $partsChecklist->boardingLadder = $_POST["boardingLadder"];
        $partsChecklist->boardingRamp = $_POST["boardingRamp"];
        $partsChecklist->reflectiveTape = $_POST["reflectiveTape"];
        $partsChecklist->seaAnchor = $_POST["seaAnchor"];
        $partsChecklist->innerOuterLifeLine = $_POST["inner/outerLifeLine"];
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
        $partsChecklist->cvSerialNum = $_POST["cylinderValveSerialNumber"];
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
        $partsChecklist->firingHeadSerialNum = $_POST["firingHeadSerialNumber"];
        $partsChecklist->userID_Sig1 = $_POST["userID_Sig1"];
        $partsChecklist->userID_Sig2 = $_POST["userID_Sig2"];
        $partsChecklist->userID_Sig1_Filepath = $_POST["userID_Sig1_Filepath"];
        $partsChecklist->userID_Sig2_Filepath = $_POST["userID_Sig2_Filepath"];
        //End Component Section ********************************************************************************************************************************

        //Survival Fields ********************************************************************************************************************************
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
        // End Survival Fields ********************************************************************************************************************************

        //Dated Items Fields ********************************************************************************************************************************
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
        //End Dated items Fields ********************************************************************************************************************************

        //Add/Update/Fill Fields Section ************************************************************************************************************************
        //Simple check to see if the sheet already exists when we post data. Creates if not, updates if so. 
        if($partsChecklist->checkIfExists($partsChecklist->sheetID))
            $result = $partsChecklist->UpdateFullTable();
        else
            $result = $partsChecklist->AddFullTable();

        //Feedback
        if($result != ""){
            addChange($_SESSION['userID'], date("Y-m-d"), $_GET["woNumber"], "Updated parts checklist");
            $result = "&#9989; Update Successful &#9989;";
        }
        $componentValues = $partsChecklist->getComponentsList($woNum);
        for($i=0; $i<count($componentValues); $i++){
            if($componentValues[$i]["sheetID"] == "PC" . $woNum){ //Results could return several sheets. Only take the one we want (PC + woNum)
                $tempComp = $componentValues[$i];
                $componentValues = [];
                $componentValues[0] = $tempComp;
            }
        }
        //Fill other table info using sheet id from component table. 
        $survivalValues = $partsChecklist->getSurvivalList($partsChecklist->sheetID);
        $datedItemsValues = $partsChecklist->getDatedItems($partsChecklist->sheetID);
        $sheetExists = true;
    } // Get Request Section ********************************************************************************************************************************
    elseif(isGetRequest()){
        $woNum = $_GET["woNumber"];
        $componentValues = $partsChecklist->getComponentsList($woNum);
        for($i = 0; $i < count($componentValues); $i++){ //Only get the record with the sheet ID we want. 
            if($componentValues[$i]["sheetID"] == "PC" . $woNum){
                $tempComp = $componentValues[$i];
                $componentValues = [];
                $componentValues[0] = $tempComp;
            }
            elseif($i == count($componentValues) -1){ //if the sheet doesn't yet exists, create a blank array and initialize important fields. 
                $componentValues[0] = [];
                $componentValues[0]["userID_Sig1"] = 0;
                $componentValues[0]["userID_Sig2"] = 0;
                $componentValues[0]["userID_Sig1_Filepath"] = '#';
                $componentValues[0]["userID_Sig2_Filepath"] = '#';
            }
        }
        if(count($componentValues) > 0){ //if the sheet does exists, get the other two tables info as well. 
            $sheetExists = true;
            $survivalValues = $partsChecklist->getSurvivalList($componentValues[0]["sheetID"]);
            $datedItemsValues = $partsChecklist->getDatedItems($componentValues[0]["sheetID"]);
        }
        else{ //Otherwise, initialize important fields and set other table arrays to blank arrays. 
            $componentValues[0] = [];
            $componentValues[0]["userID_Sig1"] = 0;
            $componentValues[0]["userID_Sig2"] = 0;
            $componentValues[0]["userID_Sig1_Filepath"] = '#';
            $componentValues[0]["userID_Sig2_Filepath"] = '#';
            $survivalValues = [];
            $datedItemsValues = [];           
        }

    }
?>

    <div class="container justify-content-center h-100">
        <div class="text-white text-center d-flex justify-content-between align-items-center">
            <div class="topDivs d-flex justify-content-start align-items-start mb-auto mt-2">
                <button id="backButton" class="btn btn-info text-white font-weight-bold">< Back</button>
            </div>
            <div class="pt-3 pb-3">
                <h1 class="text-white text-center"><b>Parts Checklist</b></h1>
                <div id="results" class="text-center text-white"><?= $result; ?></div>
            </div>
            <div class="topDivs"></div>
        </div>
        <form method="post">
            <!-- Begin Component Section -->
            <table class="table table-borderless table-striped text-white text-center align-text-center">
                <thead>
                    <tr>
                        <th>LIFERAFT COMPONENTS</th>
                        <th class="border border-top-0 border-bottom-0 border-2">INSPECTED</th>
                        <th>REPAIRED/REPLACED</th>
                    </tr>
                </thead>
                <!--Fill component table. The first column is the item name, stored in the class with a field name starting with 'name' so we can iterate in a single foreach -->
                <tbody id="componentTable">
                    <?php foreach($partsChecklist as $fieldName=>$value): ?>
                        <?php if(str_contains($fieldName, "name")){ ?>
                            <tr>
                                <td class="text-start"><span><?php echo($value)?></span></td>
                                <td class="border border-top-0 border-bottom-0 border-2">
                                    <div class="d-flex justify-content-around">
                                        <!-- <div> -->
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
            <!-- End Component Section -->
            <hr class="m-0 text-secondary opacity-100 border border-secondary border-1">
            <!-- Same as Component, but the field name start with label so we only grab the fields we want -->
            <table class="table table-borderless table-striped text-white text-center align-text-center mt-3">
                <thead>
                    <tr>
                        <th>Survival Equipment</th>
                        <th class="border border-top-0 border-bottom-0 border-2">Quantity</th>
                        <th>Quantity REPLACED</th>
                    </tr>
                </thead>
                <tbody id="survivalTable">
                    <?php foreach($partsChecklist as $fieldName=>$value): ?>
                        <?php if(str_contains($fieldName, "label")){ ?>
                        <tr>
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
            <!-- End Survival Section -->
            <hr class="m-0 text-secondary opacity-100 border border-secondary border-1">
            <!-- One more time for Dated Items.  -->
            <table  class="table table-borderless table-striped text-white text-center mt-3">
                <thead>
                    <tr>
                        <th>Dated Items</th>
                        <th class="border border-top-0 border-bottom-0 border-2">MFG Date</th>
                        <th>Expiry Date</th>
                        <th class="border border-top-0 border-bottom-0 border-2">Total Quantity</th>
                        <th>Quantity Replaced</th>
                    </tr>
                </thead>
                <tbody id="datedItemsTable">
                    <?php foreach($partsChecklist as $fieldName=>$value): ?>
                        <?php if(str_contains($fieldName, "dated")){ ?>
                        <tr>
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
            <!-- End Dated Items Section -->
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
        </form>

        <p class="text-white text-center">This is to certify that the inflatable Liferaft detailed above has been serviced as per factory specification and by an appoved service station in strict accordance with all directives of the manufacturer.</p>
        <p class="text-white text-center">This certificate is valid for Twelve Mounths from date of issue. Comments: 12</p>

        <div class="text-center">
            <button id="saveButton" class="btn btn-secondary text-white font-weight-bold mb-3">Save</button>
        </div>

        <footer>
            <hr class="m-0 text-secondary opacity-100 border border-secondary border-1">
            <div class="p-1">
        </footer>
    </div>
</body>
</html>
<script src="scripts/sigPad.js"></script>
<script>
    //Query Selector Section **********************************************************************************************************************************
    var backButton = document.querySelector(`#backButton`);
    var saveButton = document.querySelector(`#saveButton`);
    var form = document.querySelector(`form`);
    var woNum = <?= json_encode($woNum); ?>;
    var allInputs = document.querySelectorAll(`input`);
    var componentYesRadioInputs = document.querySelectorAll(`.componentsYesRadio`);
    var componentNaRadioInputs = document.querySelectorAll(`.componentsNaRadio`);
    var componentInputs = document.querySelectorAll(`.componentInputs`);
    var componentRadioInputs = document.querySelectorAll(`.componentsRadio`);
    var componentFieldInputs = document.querySelectorAll(`.componentsField`);
    var componentTable = document.querySelector(`#componentTable`);
    var survivalTable = document.querySelector(`#survivalTable`);
    var datedItemsTable = document.querySelector(`#datedItemsTable`);
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
    var acctType = <?= json_encode($_SESSION["accountType"]) ?>;

    //SigPad Event Listeners **************************************************************************************************************************************
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

    //Called on page load. 
    function fillPartsChecklist(){
        fillComponentChecklist();
        fillSurvivalChecklist();
        fillDatedItemsChecklist();
        sigPadCheck();
        if(acctType != "Supervisor" && acctType != "Admin"){
            sigDiv2.hidden = true;
            if(isSigned2){
                sigImg2.hidden = false;
            }
        }
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
            isSigned = true; //this is checked on form submit to see if we have a new sig to save or not. 
        }
        else{
            //console.log("Got Here");
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
            //console.log("Got Here 2");
            sigDiv2.hidden = false;
            sigImg2.hidden = true;
            isSigned2 = false;
        }
        //Disable all inputs on the page if there are two signatures. 
        if(isSigned && isSigned2){
            allInputs = document.querySelectorAll('input');
            for(let i=0; i<allInputs.length;i++){
                allInputs[i].disabled = true;
                allInputs[i].classList.add("inputDisabled");
                allInputs[i].classList.remove("inputActive");
            }
        }
    }

    //Start Fill Section. Fills all input elements on page load. ********************************************************************************************
    //All information for each item is stored in a single field in the database, each column value separated by a comma. While filling the table, we split the value
    //Into an array, and put each piece in it's relevant field on the table. 
    function fillComponentChecklist(){
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

    function fillSurvivalChecklist(){
        let sheetExists = <?= json_encode($sheetExists); ?>;
        if(!sheetExists){
            return;
        }
        let compObj = <?= json_encode($survivalValues); ?>;
        let parsedValues = [];

        for(let i = 0; i < survivalTable.rows.length; i++){
            Object.keys(compObj[0]).forEach(key => {
                //parse here
                if(key != "sheetID" && key != "woNum")
                    parsedValues = compObj[0][key].split(",");
                    
                if(key == survivalTable.rows[i].cells[1].childNodes[1].name){
                    survivalTable.rows[i].cells[1].childNodes[1].value = parsedValues[0];
                    survivalTable.rows[i].cells[2].childNodes[1].value = parsedValues[1];
                }
            })
        }        
    }

    function fillDatedItemsChecklist(){
        let sheetExists = <?= json_encode($sheetExists); ?>;
        if(!sheetExists){
            return;
        }
        let compObj = <?= json_encode($datedItemsValues); ?>;
        let parsedValues = [];

        for(let i = 0; i < datedItemsTable.rows.length; i++){
            Object.keys(compObj[0]).forEach(key => {
                //parse here
                if(key != "sheetID" && key != "woNum")
                    parsedValues = compObj[0][key].split(",");
                    
                if(key == datedItemsTable.rows[i].cells[1].childNodes[1].name){
                    datedItemsTable.rows[i].cells[1].childNodes[1].value = parsedValues[0];
                    datedItemsTable.rows[i].cells[2].childNodes[1].value = parsedValues[1];
                    datedItemsTable.rows[i].cells[3].childNodes[1].value = parsedValues[2];
                    datedItemsTable.rows[i].cells[4].childNodes[1].value = parsedValues[3];
                }
            })
        }        
    }
    //End Fill Section.  ************************************************************************************************************************************

    //Because of how we are storing the values in the database (single field), on save, we get all our values, concat them, and rebuild the form before submitting. 
    saveButton.addEventListener(`click`, (e) => {
        e.preventDefault();
        if(form.checkValidity()){
            SaveIt();
            let submission = GetComponentFields();
            submission += GetSurvivalFields();
            submission += GetDatedItemsFields();

            form.hidden = true; //On submit, you can see the rebuilt table for a second before the page reloads. Looks bad, so we hid it. 
            form.innerHTML = "";
            form.innerHTML = submission;
            form.submit();
        }
        else{
            form.reportValidity(); 
        }
    })

    function GetComponentFields(){
        let recordName = [];
        let componentsFinalValue = [];

        let comSubmission = `<input type="text" name="woNumber" value=${woNum}>`;
        comSubmission += `<input type="text" name="sheetID" value="PC${woNum}">`;

        for(let i = 0; i < componentTable.rows.length; i++){
            let radioValue = "";
            if(componentYesRadioInputs[i].checked == true){
                radioValue = "1";
            }
            else if(componentNaRadioInputs[i].checked == true){
                radioValue = "2";
            }
            else{
                radioValue = "0";
            }

            nameSplit = componentTable.rows[i].cells[0].childNodes[0].innerHTML.split(' ');
            recordName[i] = nameSplit[0].toLowerCase();

            for(let n = 1; n < nameSplit.length; n++){
                recordName[i] += nameSplit[n];
            }
            //console.log(recordName[i]);

            componentsFinalValue[i] = radioValue + "," + componentTable.rows[i].cells[2].childNodes[1].value;
            comSubmission += `<input type="text" name=${recordName[i]} value="${componentsFinalValue[i]}">`; 
        }
        // Section for getting the blob data from the input and putting it in the new form.  *********************************************
        comSubmission += `<input type="text" name=${blobInput.name} value="${blobInput.value}">`;
        comSubmission += `<input type="text" name=${blobInputTwo.name} value="${blobInputTwo.value}">`;

        if(!isSigned)
            userID_Sig1.value = "0"; //If we don't have any signature at all, store a 0 in the User ID field. 
        if(!isSigned2)
            userID_Sig2.value = "0";
            
        comSubmission += `<input class="inputActive" name="${userID_Sig1.name}" type="number" id="userID_Sig1" value="${userID_Sig1.value}" hidden />`;
        comSubmission += `<input class="inputActive" name="${userID_Sig2.name}" type="number" id="userID_Sig1" value="${userID_Sig2.value}" hidden />`;
        //End Signature pad section *****************************************************************************************************

        return comSubmission;
    }

    function GetSurvivalFields(){
        let recordName = [];
        let survivalFinalValue = [];
        let surSubmission = "";


        for(let i = 0; i < survivalTable.rows.length; i++){
            recordName[i] = survivalTable.rows[i].cells[1].childNodes[1].name;

            survivalFinalValue[i] = survivalTable.rows[i].cells[1].childNodes[1].value + "," + survivalTable.rows[i].cells[2].childNodes[1].value;
            surSubmission += `<input type="text" name=${recordName[i]} value="${survivalFinalValue[i]}">`; 
        }
        return surSubmission;
    }

    function GetDatedItemsFields(){
        let recordName = [];
        let datedItemsFinalValue = [];
        let datSubmission = "";


        for(let i = 0; i < datedItemsTable.rows.length; i++){
            recordName[i] = datedItemsTable.rows[i].cells[1].childNodes[1].name;

            datedItemsFinalValue[i] = datedItemsTable.rows[i].cells[1].childNodes[1].value + "," + datedItemsTable.rows[i].cells[2].childNodes[1].value + "," + datedItemsTable.rows[i].cells[3].childNodes[1].value + "," + datedItemsTable.rows[i].cells[4].childNodes[1].value;
            datSubmission += `<input type="text" name=${recordName[i]} value="${datedItemsFinalValue[i]}">`;
            //console.log(recordName[i] + datedItemsFinalValue[i]); 
        }

        return datSubmission;
    }
    //End Form Rebuild Section.  ************************************************************************************************************************************

    backButton.addEventListener(`click`, (e) => {
        window.location = 'viewWorkOrder.php?woNumber=' + woNum;
    })

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
            userID_Sig1.value = `<?= $componentValues[0]["userID_Sig1"] ?>`;
        }
        if(isSigned2 && newSig2){
            var image2 = c[1].toDataURL("image/png");
            blobInputTwo.value = image2;
        }
        else if(isSigned2 && !newSig2){
            blobInputTwo.value = sigImg2.src
            userID_Sig2.value = `<?= $componentValues[0]["userID_Sig2"] ?>`;

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