<?php
include('includes/header.php');
include('model/model_qc_checklist.php');
include('includes/functions.php');
include('model/model_change_log.php');

$feedback = "";

if(!isset($_GET["woNumber"])){ //No URL Param? No Good. Go Home. 
    header("Location: home.php");
}
//POST Section ******************************************************************************************************************************
if(filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST')
{
    $qcChecklist["woNum"] = $_POST["woNumber"];
    $qcChecklist = getQCChecklist($_POST["woNumber"]);
    $vesselID = getVesselID($_POST["woNumber"]);

    $qcChecklist["item1"] = $_POST["item1"];
    $qcChecklist["item2"] = $_POST["item2"];
    $qcChecklist["item3"] = $_POST["item3"];
    $qcChecklist["item4"] = $_POST["item4"];
    $qcChecklist["item5"] = $_POST["item5"];
    $qcChecklist["item5A"] = $_POST["item5A"];
    $qcChecklist["item5B"] = $_POST["item5B"];
    $qcChecklist["item6"] = $_POST["item6"];
    if($_POST["item6A"] == "") //The ifs in the part set the fields to null so we can store appropriately in the DB
    {
        $qcChecklist["item6A"] = null;
    }
    else
    {
        $qcChecklist["item6A"] = $_POST["item6A"];
    }
    $qcChecklist["item6B"] = $_POST["item6B"];
    $qcChecklist["item7"] = $_POST["item7"];
    if($_POST["item7A"] == "")
    {
        $qcChecklist["item7A"] = null;
    }
    else
    {
        $qcChecklist["item7A"] = $_POST["item7A"];
    }
    if($_POST["item7B"] == "")
    {
        $qcChecklist["item7B"] = null;
    }
    else
    {
        $qcChecklist["item7B"] = $_POST["item7B"];
    }
    if($_POST["item7C"] == "")
    {
        $qcChecklist["item7C"] = null;
    }
    else
    {
        $qcChecklist["item7C"] = $_POST["item7C"];
    }
    $qcChecklist["item8"] = $_POST["item8"];
    $qcChecklist["item8A"] = $_POST["item8A"];
    $qcChecklist["item8B"] = $_POST["item8B"];
    if($_POST["item8C"] == "")
    {
        $qcChecklist["item8C"] = null;
    }
    else
    {
        $qcChecklist["item8C"] = $_POST["item8C"];
    }
    $qcChecklist["item9"] = $_POST["item9"];
    $qcChecklist["item10"] = $_POST["item10"];
    $qcChecklist["item10A"] = $_POST["item10A"];
    $qcChecklist["item11"] = $_POST["item11"];
    $qcChecklist["item12"] = $_POST["item12"];
    $qcChecklist["item13"] = $_POST["item13"];
    $qcChecklist["item14"] = $_POST["item14"];
    $qcChecklist["item15"] = $_POST["item15"];
    $qcChecklist["item16"] = $_POST["item16"];

    $qcChecklist["userID_Sig1_Filepath"] = $_POST["userID_Sig1_Filepath"];
    $qcChecklist["userID_Sig1"] = $_POST["userID_Sig1"];        
    
    $qcChecklist["userID_Sig2_Filepath"] = $_POST["userID_Sig2_Filepath"];
    $qcChecklist["userID_Sig2"] = $_POST["userID_Sig2"];  

    $result = updateQCChecklist($qcChecklist); //Only call update instead of add, since we create a record on load no matter what. 

    if($result == true)
    {
        addChange($_SESSION['userID'], date("Y-m-d"), $_GET["woNumber"], "Updated QC checklist"); //Update Changelog
        $feedback = "&#9989; Update Successful &#9989;";
    }
}//End POST Section, Begin GET ******************************************************************************************************************************
else if(isGetRequest()){
    $qcChecklist = getQCChecklist($_GET["woNumber"]);
    $vesselID = getVesselID($_GET["woNumber"]);

    if($qcChecklist == [])//This page creates a new record in DB on load if one doesn't already exists. 
    {
        addQCCecklist($_GET["woNumber"]);
        $qcChecklist = getQCChecklist($_GET["woNumber"]);
    }
}
//End GET Section ******************************************************************************************************************************
?>
    <div class="container justify-content-center h-100">
        <div class="text-white text-center d-flex justify-content-between align-items-center">
            <div class="topDivs d-flex justify-content-start align-items-start mb-auto mt-2">
                <button id="backButton" class="btn btn-info text-white font-weight-bold">< Back</button>
            </div>
            <div class="pt-3 pb-3">
                <h1 class="text-white text-center"><b>Life Raft Quality Control Checklist</b></h1>
                <div id="results" class="text-center text-white"><?= $feedback; ?></div>
            </div>
            <div class="topDivs"></div>
        </div>
        <div id="heading" class="text-white text-center d-flex justify-content-center align-items-center">
            <p class="mx-3" style="width: 25%">Work Order #: <u><?= $_GET["woNumber"]; ?></u></p>
            <p class="mx-3" style="width: 25%">Life Raft Serial #: <u><?= $vesselID["vesselID"] ?></u></p>
        </div>
        <hr class="m-0 text-secondary opacity-100 border border-secondary border-1">
        <!-- Build Checklist, with yes/na checkboxes and qty replaced fields-->
        <form method="post">
            <input type="hidden" name="woNumber" value=<?= $qcChecklist["woNum"]; ?>/> 
            <table class="table table-borderless table-striped text-white align-text-center">
                <thead>
                    <tr class="text-center">
                        <th>Yes</th>
                        <th>N/A</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><label class="yesCheckboxContainer"><input type="checkbox" name="item1" class="yes" value="1" <?php if($qcChecklist["item1"] == 1): ?>checked<?php endif; ?>><span class="yesCheckmark"></span></label></td>
                        <td><label class="naCheckboxContainer"><input type="checkbox" name="item1" class="na" value="2" <?php if($qcChecklist["item1"] == 2): ?>checked<?php endif; ?>><span class="naCheckmark"></span></label></td>
                        <td><p class="lineItem m-0">Check QB for new notes & change state when opened</p></td>
                    </tr>
                    <tr>
                        <td><label class="yesCheckboxContainer"><input type="checkbox" name="item2" class="yes" value="1" <?php if($qcChecklist["item2"] == 1): ?>checked<?php endif; ?>><span class="yesCheckmark"></span></label></td>
                        <td><label class="naCheckboxContainer"><input type="checkbox" name="item2" class="na" value="2" <?php if($qcChecklist["item2"] == 2): ?>checked<?php endif; ?>><span class="naCheckmark"></span></label></td>
                        <td><p class="lineItem m-0">Blast Test</p></td>
                    </tr>
                    <tr>
                        <td><label class="yesCheckboxContainer"><input type="checkbox" name="item3" class="yes" value="1" <?php if($qcChecklist["item3"] == 1): ?>checked<?php endif; ?>><span class="yesCheckmark"></span></label></td>
                        <td><label class="naCheckboxContainer"><input type="checkbox" name="item3" class="na" value="2" <?php if($qcChecklist["item3"] == 2): ?>checked<?php endif; ?>><span class="naCheckmark"></span></label></td>
                        <td><p class="lineItem m-0">Match container Approved Number to life raft Approved Number</p></td>
                    </tr>
                    <tr>
                        <td><label class="yesCheckboxContainer"><input type="checkbox" name="item4" class="yes" value="1" <?php if($qcChecklist["item4"] == 1): ?>checked<?php endif; ?>><span class="yesCheckmark"></span></label></td>
                        <td><label class="naCheckboxContainer"><input type="checkbox" name="item4" class="na" value="2" <?php if($qcChecklist["item4"] == 2): ?>checked<?php endif; ?>><span class="naCheckmark"></span></label></td>
                        <td><p class="lineItem m-0">Match container Serial Number & DOM to life raft hull Serial Number & DOM</p></td>
                    </tr>
                    <tr>
                        <td><label class="yesCheckboxContainer"><input type="checkbox" name="item5" class="yes" value="1" <?php if($qcChecklist["item5"] == 1): ?>checked<?php endif; ?>><span class="yesCheckmark"></span></label></td>
                        <td><label class="naCheckboxContainer"><input type="checkbox" name="item5" class="na" value="2" <?php if($qcChecklist["item5"] == 2): ?>checked<?php endif; ?>><span class="naCheckmark"></span></label></td>
                        <td>
                            <p class="lineItem m-0">Match container painter to life raft. Painter length is:
                            <input type="text" name="item5A" value="<?= $qcChecklist["item5A"] ?>" class="rounded inputActive" maxlength="10">
                            m. Stowage is:  
                            <input type="text" name="item5B" value="<?= $qcChecklist["item5B"] ?>" class="rounded inputActive" maxlength="10"> m</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label class="yesCheckboxContainer"><input type="checkbox" name="item6" class="yes" value="1" <?php if($qcChecklist["item6"] == 1): ?>checked<?php endif; ?>><span class="yesCheckmark"></span></label></td>
                        <td><label class="naCheckboxContainer"><input type="checkbox" name="item6" class="na" value="2" <?php if($qcChecklist["item6"] == 2): ?>checked<?php endif; ?>><span class="naCheckmark"></span></label></td>
                        <td>
                            <p class="lineItem m-0">GIS(black) or GIST(white) firing head (leafield) expires:
                            <input type="date" name="item6A" value="<?= $qcChecklist["item6A"] ?>" class="rounded inputActive">
                            Serial #:  
                            <input type="text" name="item6B" value="<?= $qcChecklist["item6B"] ?>" class="rounded inputActive" maxlength="10"></p>
                        </td>
                    </tr>
                    <tr>
                        <td><label class="yesCheckboxContainer"><input type="checkbox" name="item7" class="yes" value="1" <?php if($qcChecklist["item7"] == 1): ?>checked<?php endif; ?>><span class="yesCheckmark"></span></label></td>
                        <td><label class="naCheckboxContainer"><input type="checkbox" name="item7" class="na" value="2" <?php if($qcChecklist["item7"] == 2): ?>checked<?php endif; ?>><span class="naCheckmark"></span></label></td>
                        <td>
                            <p class="lineItem m-0">Expiration dates on Top Hose:
                            <input type="date" name="item7A" value="<?= $qcChecklist["item7A"] ?>" class="rounded inputActive">
                            Bottom Hose:  
                            <input type="date" name="item7B" value="<?= $qcChecklist["item7B"] ?>" class="rounded inputActive">
                            or PRVs:  
                            <input type="date" name="item7C" value="<?= $qcChecklist["item7C"] ?>" class="rounded inputActive"></p>
                        </td>
                    </tr>
                    <tr>
                        <td><label class="yesCheckboxContainer"><input type="checkbox" name="item8" class="yes" value="1" <?php if($qcChecklist["item8"] == 1): ?>checked<?php endif; ?>><span class="yesCheckmark"></span></label></td>
                        <td><label class="naCheckboxContainer"><input type="checkbox" name="item8" class="na" value="2" <?php if($qcChecklist["item8"] == 2): ?>checked<?php endif; ?>><span class="naCheckmark"></span></label></td>
                        <td>
                            <p class="lineItem m-0">Firing head type:
                            <input type="text" name="item8A" value="<?= $qcChecklist["item8A"] ?>" class="rounded inputActive" maxlength="10">
                            Serial #:  
                            <input type="text" name="item8B" value="<?= $qcChecklist["item8B"] ?>" class="rounded inputActive" maxlength="10">
                            Expires:  
                            <input type="date" name="item8C" value="<?= $qcChecklist["item8C"] ?>" class="rounded inputActive"></p>
                        </td>
                    </tr>
                    <tr>
                        <td><label class="yesCheckboxContainer"><input type="checkbox" name="item9" class="yes" value="1" <?php if($qcChecklist["item9"] == 1): ?>checked<?php endif; ?>><span class="yesCheckmark"></span></label></td>
                        <td><label class="naCheckboxContainer"><input type="checkbox" name="item9" class="na" value="2" <?php if($qcChecklist["item9"] == 2): ?>checked<?php endif; ?>><span class="naCheckmark"></span></label></td>
                        <td><p class="lineItem m-0">NAP test or floor seam test</p></td>
                    </tr>
                    <tr>
                        <td><label class="yesCheckboxContainer"><input type="checkbox" name="item10" class="yes" value="1" <?php if($qcChecklist["item10"] == 1): ?>checked<?php endif; ?>><span class="yesCheckmark"></span></label></td>
                        <td><label class="naCheckboxContainer"><input type="checkbox" name="item10" class="na" value="2" <?php if($qcChecklist["item10"] == 2): ?>checked<?php endif; ?>><span class="naCheckmark"></span></label></td>
                        <td>
                            <p class="lineItem m-0">Trigger for lights. Attached light type:
                            <input type="text" name="item10A" value="<?= $qcChecklist["item10A"] ?>" class="rounded inputActive" maxlength="10"></p>
                        </td>
                    </tr>
                    <tr>
                        <td><label class="yesCheckboxContainer"><input type="checkbox" name="item11" class="yes" value="1" <?php if($qcChecklist["item11"] == 1): ?>checked<?php endif; ?>><span class="yesCheckmark"></span></label></td>
                        <td><label class="naCheckboxContainer"><input type="checkbox" name="item11" class="na" value="2" <?php if($qcChecklist["item11"] == 2): ?>checked<?php endif; ?>><span class="naCheckmark"></span></label></td>
                        <td><p class="lineItem m-0">Fully filled out certificate (self check)</p></td>
                    </tr>
                    <tr>
                        <td><label class="yesCheckboxContainer"><input type="checkbox" name="item12" class="yes" value="1" <?php if($qcChecklist["item12"] == 1): ?>checked<?php endif; ?>><span class="yesCheckmark"></span></label></td>
                        <td><label class="naCheckboxContainer"><input type="checkbox" name="item12" class="na" value="2" <?php if($qcChecklist["item12"] == 2): ?>checked<?php endif; ?>><span class="naCheckmark"></span></label></td>
                        <td><p class="lineItem m-0">ID tubes paperwork filled out. (serial #, boat name, etc)</p></td>
                    </tr>
                    <tr>
                        <td><label class="yesCheckboxContainer"><input type="checkbox" name="item13" class="yes" value="1" <?php if($qcChecklist["item13"] == 1): ?>checked<?php endif; ?>><span class="yesCheckmark"></span></label></td>
                        <td><label class="naCheckboxContainer"><input type="checkbox" name="item13" class="na" value="2" <?php if($qcChecklist["item13"] == 2): ?>checked<?php endif; ?>><span class="naCheckmark"></span></label></td>
                        <td><p class="lineItem m-0">Certified technician or checker confirms Correct Folds</p></td>
                    </tr>
                    <tr>
                        <td><label class="yesCheckboxContainer"><input type="checkbox" name="item14" class="yes" value="1" <?php if($qcChecklist["item14"] == 1): ?>checked<?php endif; ?>><span class="yesCheckmark"></span></label></td>
                        <td><label class="naCheckboxContainer"><input type="checkbox" name="item14" class="na" value="2" <?php if($qcChecklist["item14"] == 2): ?>checked<?php endif; ?>><span class="naCheckmark"></span></label></td>
                        <td><p class="lineItem m-0">Certified technician or checker confirms painter line connections</p></td>
                    </tr>
                    <tr>
                        <td><label class="yesCheckboxContainer"><input type="checkbox" name="item15" class="yes" value="1" <?php if($qcChecklist["item15"] == 1): ?>checked<?php endif; ?>><span class="yesCheckmark"></span></label></td>
                        <td><label class="naCheckboxContainer"><input type="checkbox" name="item15" class="na" value="2" <?php if($qcChecklist["item15"] == 2): ?>checked<?php endif; ?>><span class="naCheckmark"></span></label></td>
                        <td><p class="lineItem m-0">Fabric secure in canister</p></td>
                    </tr>
                    <tr>
                        <td><label class="yesCheckboxContainer"><input type="checkbox" name="item16" class="yes" value="1" <?php if($qcChecklist["item16"] == 1): ?>checked<?php endif; ?>><span class="yesCheckmark"></span></label></td>
                        <td><label class="naCheckboxContainer"><input type="checkbox" name="item16" class="na" value="2" <?php if($qcChecklist["item16"] == 2): ?>checked<?php endif; ?>><span class="naCheckmark"></span></label></td>
                        <td><p class="lineItem m-0">Check painter line rope extension condition</p></td>
                    </tr>
                </tbody>
            </table> 
            <!-- End Checklist Table Section-->
            <!-- Begin Signature Pad Section-->
                <div class="sigStuff text-white d-flex justify-content-around">
                    <div id="sigDiv1">
                        <input class="inputActive" name="userID_Sig1" type="number" id="userID_Sig1" value="<?= $_SESSION['userID']; ?>" hidden/>
                        <input class="inputActive" name="userID_Sig1_Filepath" type="text" id="blobData" hidden/>
                        <canvas class="bg-white rounded" width="400" height="300"></canvas>
                        <div class="d-flex justify-content-around">
                            <button id="sigSave1" class="btn btn-secondary text-white font-weight-bold">Save Signature</button>
                            <button class="clearButtons btn btn-info text-white font-weight-bold">Clear Signature</button>
                        </div>
                    </div>
                    <img id="sigImage" src="<?= $qcChecklist["userID_Sig1_Filepath"]; ?>" class="bg-white rounded"/>
                    <div id="sigDiv2">
                        <input class="inputActive" name="userID_Sig2" type="number" id="userID_Sig2" value="<?= $_SESSION['userID']; ?>" hidden/>
                        <input class="inputActive" name="userID_Sig2_Filepath" type="text" id="blobDataTwo" hidden/>   
                        <canvas class="bg-white rounded" width="400" height="300"></canvas>
                        <div class="d-flex justify-content-around">
                            <button id="sigSave2" class="btn btn-secondary text-white font-weight-bold">Save Signature</button>
                            <button class="clearButtons btn btn-info text-white font-weight-bold">Clear Signature</button>
                        </div>
                    </div>
                    <img id="sigImage2" src="<?= $qcChecklist["userID_Sig2_Filepath"]; ?>" class="bg-white rounded"/>
                </div>
                <!-- End Signature Pad Section-->
            <br>
            <div class="d-flex justify-content-center">
                <button type="submit" id="saveBtn" class="btn btn-secondary text-white font-weight-bold">Save</button>  
            </div>      
        </form>
        <p id="error"></p>

        <footer>
            <hr class="m-0 text-secondary opacity-100 border border-secondary border-1">
            <div class="p-1">
        </footer>
    </div>
</body>
</html>
<script src="scripts/sigPad.js"></script>
<script>
    //Query Selectors ******************************************************************************************************
    var backButton = document.querySelector(`#backButton`);
    var saveButton = document.querySelector(`#saveBtn`);
    var yesCheck = document.querySelectorAll(`.yes`);
    var naCheck = document.querySelectorAll(`.na`);
    var form = document.querySelector(`form`);
    var blobInputs = document.querySelectorAll(`.blobData`);
    var allInputs = document.querySelectorAll(`input[type='checkbox']`);
    var textInputs = document.querySelectorAll(`input.inputActive`);
    var userID_Sig1 = document.querySelector(`#userID_Sig1`);
    var userID_Sig2 = document.querySelector(`#userID_Sig2`);
    var sigImg = document.querySelector(`#sigImage`);
    var sigImg2 = document.querySelector(`#sigImage2`);
    var sigDiv1 = document.querySelector('#sigDiv1');
    var sigDiv2 = document.querySelector('#sigDiv2');
    var sigSave1 = document.querySelector('#sigSave1');
    var sigSave2 = document.querySelector('#sigSave2');
    var clr = document.querySelectorAll(`.clearButtons`);
    //Query Selectors ******************************************************************************************************
    
    //Vars for checking if sig is already signed and/or being signed. 
    var isSigned = false;
    var isSigned2 = false;
    var newSig1 = false;
    var newSig2 = false;

    //Event Listeners *****************************************************************************************************
    backButton.addEventListener(`click`, (e) => {
        window.location = 'viewWorkOrder.php?woNumber=<?= $_GET["woNumber"]; ?>';
    })

    //Loop through the checkboxes and see if they need to be checked, also adds event listeners. 
    for(let i=0; i < yesCheck.length; i++)
    {
        yesCheck[i].addEventListener(`change`, (e) => {
            if(naCheck[i].checked && yesCheck[i].checked)
            {
                naCheck[i].checked = false;
            }
        })

        naCheck[i].addEventListener(`change`, (e) => {
            if(naCheck[i].checked && yesCheck[i].checked)
            {
                yesCheck[i].checked = false;
            }
        })
    }
    
    //On save, get checkbox values, input fields, rebuild the form
    saveButton.addEventListener('click', (e) => {
        e.preventDefault();
        GetCheckboxValues();
        SaveIt();
        GetInputAndDates(); 
        form.submit();
    })

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

    //On load, check the check the UserID fields of sig pads to see if there are signatures present or not. 
    //If so, display image with Base64 as source, if not, display sig pads. 
    //Other pages have this in a function called on load. Something on this page prevented that from happening, so we do it like this. 
    window.addEventListener('load', function(){ 
        let sigCheck1 = '0';
        let sigCheck2 = '0'; 
        if(`<?= (string)$qcChecklist["userID_Sig1"] ?>` != ``)
            sigCheck1 = `<?= (string)$qcChecklist["userID_Sig1"]; ?>`;
        if(`<?= (string)$qcChecklist["userID_Sig2"] ?>` != ``)
            sigCheck2 = `<?= (string)$qcChecklist["userID_Sig2"]; ?>`;

        if(sigCheck1 != '0'){
            sigDiv1.hidden = true;
            sigImg.hidden = false;
            isSigned = true;
            userID_Sig1.value = `<?= $qcChecklist["userID_Sig1"] ?>`;
        }
        else{
            sigDiv1.hidden = false;
            sigImg.hidden = true;
            isSigned = false;
        }
        if(sigCheck2 != '0'){
            sigDiv2.hidden = true;
            sigImg2.hidden = false;
            isSigned2 = true;
            userID_Sig2.value = `<?= $qcChecklist["userID_Sig2"] ?>`;
        }
        else{
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
            }
        }
    })

    //Loop through checkboxes, store ones that are checked with appropriate values
    function GetCheckboxValues(){
        var checkInputs;
        for(let i=0;i<allInputs.length; i+=2){
            if(allInputs[i].checked){
                checkInputs += `<input type="checkbox" name="${allInputs[i].name}" value="1" checked>`;
            }
            else if(allInputs[i+1].checked)  {
                checkInputs += `<input type="checkbox" name="${allInputs[i].name}" value="2" checked>`;
            }   
            else{
                checkInputs += `<input type="checkbox" name="${allInputs[i].name}" value="0" checked>`;
            }
            allInputs[i].disabled = true;           
            allInputs[i+1].disabled = true; 
        }        
        form.innerHTML += checkInputs; //Add theses elements to the form
    }

    //If nothing has been signed, store 0 in userID field so we know next time. Then grab the input values and store in new element. 
    function GetInputAndDates(){
        var checkInputs;
        if(isSigned == false)
            userID_Sig1.value = 0;

        if(isSigned2 == false)
            userID_Sig2.value = 0;

        for(let i=0; i<textInputs.length; i++){
            checkInputs += `<input type="${textInputs[i].type}" name="${textInputs[i].name}" value="${textInputs[i].value}" >`;
            textInputs[i].disabled;
        }
        form.innerHTML += checkInputs; //Add theses elements to the form
        
    }

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
        }
        if(isSigned2 && newSig2){
            var image2 = c[1].toDataURL("image/png");
            blobInputTwo.value = image2;
        }
        else if(isSigned2 && !newSig2){
            blobInputTwo.value = sigImg2.src
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
