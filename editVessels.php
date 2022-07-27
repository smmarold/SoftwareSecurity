<?php
    include('includes/header.php');
    include('includes/functions.php');
    include('model/model_customer.php');
    include('model/model_change_log.php');

    date_default_timezone_set('America/New_York');

    //Initialize Vars
    $vessel = new Vessel; //vessel object for holding properties. 
    $vessels = []; 
    $noVessels = false; //Checked to see if the customer has any vessels associated yet. 
    $vesselAdded = false; 
    $vesselUpdated = false;
    $action = "Add";
    $result = "";

    $models = getvesselModels(); //Get vessel models from our Vessel Lookup table

    if(!isset($_GET["customerID"])){ //As always, no URL param means we shouldn't be here. Go Home. 
        header("Location: customers.php");
    }

    //POST Section **********************************************************************************************************************************************
    if(isPostRequest()){
        $vesselAdded = true; //Used in JavaScript to determin actions to take. 
        $action = $_POST["action"];
        $vessel->vesselID = $_POST["serialNumber"];
        $vessel->vesselModel = $_POST["vesselModel"];
        $vessel->vesselManufacturer = $_POST["vesselManufacturer"];        
        $vessel->vesselCapacity = $_POST["vesselCapacity"];
        if($_POST["vesselLastInspection"] == "")
            $vessel->lastInspection = null;
        else
            $vessel->lastInspection = $_POST["vesselLastInspection"];
        $vessel->callSign = $_POST["vesselCallSign"];
        $vessel->imoNum = $_POST["vesselIMONumber"];
        $vessel->customerID = $_POST["customerID"];
        $vessel->vesselName = $_POST["boatName"];
        $vessel->vesselFlag = $_POST["vesselFlag"];
        $vessel->classSociety = $_POST["classSociety"];
        $customerID = $vessel->customerID;

        //Determine if we are adding a new vessel or updating. 
        if($action == "Add"){
            $result = $vessel->addNewVessel();
        }
        elseif($action == "Edit"){
            $result = $vessel->UpdateVessel();
            $vesselUpdated = true;
        }
        
        $vessels = $vessel->getCustomerVessels($customerID); //Get vessels again to fill dropdowns
        $noVessels = false; 
    }//End POST Section, Start GET **************************************************************************************************************************************
    elseif(isGetRequest()){
        $customerID = $_GET["customerID"];
        $vessels = $vessel->getCustomerVessels($customerID);
        if(count($vessels) == 0)
            $noVessels = true; //If there are no associated vessels for customer, we change the form to be a blank 'Add New' section
    }
    //END GET **********************************************************************************************************************************************************
?>
    <div class="container justify-content-center h-100">
        <div class="text-white text-center d-flex justify-content-between align-items-center">
            <div class="topDivs d-flex justify-content-start align-items-start mb-auto mt-2">
                <button id="backButton" class="btn btn-info text-white font-weight-bold">< Back</button>
            </div>
            <h1 class="text-white text-center pt-3 pb-3"><b>Vessel Information</b></h1>
            <div class="topDivs"></div>
        </div>
        <!-- Vessel Section. -->
        <form method="post">
            <div class="d-flex justify-content-center align-items-center my-2 text-white">
                <!-- If there are vessels already associated with Customer, fill dropdown with it's info-->
                <label for="serialNumberDropdown" id="serialDropLabel" class="col-2 text-end" hidden>Select Customer Vessel</label>
                <select name="serialNumberDropdown" id="serialNumberDropdown" class="col-3 rounded inputActive mx-2" hidden>
                    <?php for($i=0; $i< count($vessels); $i++){ ?>
                        <option value= "<?= $vessels[$i]["vesselID"]; ?>"><?= $vessels[$i]["vesselID"]; ?></option>
                    <?php } ?>
                </select>
                
                <input type="hidden" id="hiddenAction" name="action" value="<?= $action; ?>">
                <input type="hidden" id="customerID" name="customerID" value="<?= $customerID; ?>">
                
                <!-- If there are no vessels associated, or if we are editing, they will use the Serial Num input-->
                <label for="serialNumber" id="serialFieldLabel" class="col-2 text-end">Serial Number</label>
                <input type="text" id="serialNumber" name="serialNumber" class="col-3 rounded inputActive mx-2" maxlength="50" required disabled>

                <label for="vesselModel" class="col-2 text-end">Model</label>
                <input list="vesselModels" id="model" name="vesselModel" class="col-3 rounded mx-2" maxlength="50" required/>
                <!-- Models are stored in a separate table, this datalist will allow for dropdown and search functionality in one while editing. -->
                <datalist id="vesselModels">
                    <?php foreach($models as $record): ?>
                        <option value="<?= $record["vesselModel"]; ?>"></option>
                    <?php endforeach ?>
                </datalist>
            </div>
            <div class="d-flex justify-content-center align-items-center my-2 text-white">
                <label for="boatName" class="col-2 text-end">Boat Name</label>
                <input type="text" name="boatName" class="col-3 rounded mx-2" maxlength="50" disabled>

                <label for="vesselManufacturer" class="col-2 text-end">Manufacturer</label>
                <input type="text" name="vesselManufacturer" class="col-3 rounded mx-2" maxlength="50" required disabled>
            </div>
            <div class="d-flex justify-content-center align-items-center my-2 text-white">
                <label for="vesselCapacity" class="col-2 text-end">Capacity</label>
                <input type="number" name="vesselCapacity" class="col-3 rounded mx-2" min="1" required disabled>

                <label for="vesselLastInspection" class="col-2 text-end">Last Inspection</label>
                <input type="date" name="vesselLastInspection" class="col-3 rounded mx-2" max="<?= date("Y-m-d"); ?>" disabled>
            </div>
            <div class="d-flex justify-content-center align-items-center my-2 text-white">
                <label for="vesselCallSign" class="col-2 text-end">Call Sign</label>
                <input type="text" name="vesselCallSign" class="col-3 rounded mx-2" maxlength="50" disabled>

                <label for="vesselIMONumber" class="col-2 text-end">IMO #</label>
                <input type="text" name="vesselIMONumber" class="col-3 rounded mx-2" maxlength="10" disabled>
            </div>
            <div class="d-flex justify-content-center align-items-center my-2 text-white">
                <label for="vesselFlag" class="col-2 text-end">Flag</label>
                <input type="text" name="vesselFlag" class="col-3 rounded mx-2" maxlength="50" disabled>

                <label for="classSociety" class="col-2 text-end">Class Society</label>
                <input type="text" name="classSociety" class="col-3 rounded mx-2" maxlength="50" disabled>
            </div>
            <div class="d-flex justify-content-center my-3">
                <button id="cancelEdit" class="btn btn-info text-white font-weight-bold mx-2" hidden>Cancel</button>
                <button type="submit" id="submitBtn" class="btn btn-secondary text-white font-weight-bold mx-2" name="submitBtn">Save New Vessel</button>
                <button id="continueButton" class="btn btn-secondary text-white font-weight-bold mx-2" hidden>Create Work Order</button>
                <button id="editBtn" class="btn btn-secondary text-white font-weight-bold mx-2" hidden>Edit Vessel</button>
                <button id="addNewVesselBtn" class="btn btn-secondary text-white font-weight-bold mx-2" hidden>Add New Vessel</button>
            </div>
            <p id="result" class="text-center text-white"><?= $result ?></p>
        </form>
    </div>
    <footer>
        <hr class="m-0 text-secondary opacity-100 border border-secondary border-1">
        <div class="p-1">
    </footer>
</body>
</html>

<script>
    //Query Selectors ********************************************************************************************************************
    var backButton = document.querySelector(`#backButton`);
    var serialDropdown = document.querySelector(`#serialNumberDropdown`);
    var vesselInputs = document.querySelectorAll('input');
    var submitBtn = document.querySelector('#submitBtn');
    var continueBtn = document.querySelector('#continueButton');
    var editBtn = document.querySelector('#editBtn');
    var addNew = document.querySelector('#addNewVesselBtn');
    var action = document.querySelector('#hiddenAction');
    var serialNumInput = document.querySelector('#serialNumber');
    var serialDropLabel = document.querySelector('#serialDropLabel');
    var serialFieldLabel = document.querySelector('#serialFieldLabel');
    var result = document.querySelector('#result');
    var form = document.querySelector('form');
    var cancelEdit = document.querySelector('#cancelEdit');
    var vesselModel = document.querySelector('#model');
    //End Query Selectors ********************************************************************************************************************

    var models = <?php echo json_encode($models); ?>; //Get the vessel model info

    //Event Listeners ************************************************************************************************************************
    vesselModel.addEventListener(`change`, (e)=>{ //Loop through models and try to match the input to it. 
        //Prevent the default action of button
        e.preventDefault();

        let found = false;
        let index = 0;

        if(!vesselModel.value) return;

        while(found == false && models.length > index){
            if(vesselModel.value == models[index].vesselModel){
                found = true;
                vesselInputs[5].value = models[index].vesselManufacturer;
                vesselInputs[6].value = models[index].vesselCapacity;
            }
            else{
                index++;
            }
        }

        if(found == false){
            vesselInputs[5].value.value = "";
            vesselInputs[6].value.value = "";
        }
    })

    submitBtn.addEventListener('click', (e)=> {
        e.preventDefault();
        if(form.checkValidity()){
            serialNumInput.disabled = false;
            form.submit();
        }
        else{
            form.reportValidity();
        }
    })

    backButton.addEventListener(`click`, (e) => {
        window.location = 'editCustomer.php?customerID=<?= $customerID; ?>';
    })

    //Create Work Order Button 
    continueBtn.addEventListener('click', (e) => {
        e.preventDefault();
        window.location = 'createWorkOrder.php?customerID=<?= $customerID; ?>&vesselID=' + serialDropdown.value;
    });

    editBtn.addEventListener('click', enableEdit);
    addNew.addEventListener('click', clearInputs);
    cancelEdit.addEventListener('click', CancelEdit);
    //End Event Listeners ************************************************************************************************************************

    //Called on page load. If there are no vessels, enable all fields and show the Serial Num input. 
    //Otherwise, display vessel info, the serial dropdown, and disable fields for editing. 
    function checkVessels(){
        let noVessels = <?= json_encode($noVessels); ?>;
        let vesselAdded = <?= json_encode($vesselAdded); ?>;
        
        if(noVessels){
            toggleInputsAndButtons(true);
            cancelEdit.hidden = true;
        } 
        else{
            fillVesselInfo();
            serialDropdown.addEventListener('change', fillVesselInfo)
            toggleInputsAndButtons(false);
        }
    }

    //Fill the vessel inputs based on the currently selected serial number
    function fillVesselInfo(){
        let serialNum = serialDropdown.value;
        let vesselArray = <?= json_encode($vessels); ?>;
        
        for(let i=0; i<vesselArray.length; i++){
            if(vesselArray[i].vesselID == serialNum){
                vesselInputs[2].value = vesselArray[i].vesselID;
                vesselInputs[3].value = vesselArray[i].vesselModel;
                vesselInputs[4].value = vesselArray[i].vesselName;
                vesselInputs[5].value = vesselArray[i].vesselManufacturer;
                vesselInputs[6].value = vesselArray[i].vesselCapacity;
                vesselInputs[7].value = vesselArray[i].lastInspection;
                vesselInputs[8].value = vesselArray[i].callSign;
                vesselInputs[9].value = vesselArray[i].imoNum;
                vesselInputs[10].value = vesselArray[i].vesselFlag;
                vesselInputs[11].value = vesselArray[i].classSociety;
            }
        }
    }

    //Called when edit button is clicked, changing post info to edit so we update, and toggling inputs appropriately. 
    function enableEdit(e){
        e.preventDefault();
        submitBtn.innerHTML = "Update Vessel";
        action.value = "Edit";

        toggleInputsAndButtons(true);
        serialNumInput.disabled = true;
        serialFieldLabel.disabled = true;

        result.innerHTML = "";
    }

    //Simple function for toggling inputs depending on if we are adding/editing or not. On/Off depends on what's passed to the function.
    function toggleInputsAndButtons(canEdit){
        if(canEdit)
        {
            for(let i=0; i<vesselInputs.length; i++){
                vesselInputs[i].disabled = false;
                vesselInputs[i].classList.add("inputActive");
                vesselInputs[i].classList.remove("inputDisabled");
            }

            submitBtn.hidden = false;
            addNew.hidden = true;
            continueBtn.hidden = true;
            editBtn.hidden = true;
            serialDropdown.hidden = true;
            serialDropLabel.hidden = true;
            serialFieldLabel.hidden = false;
            serialNumInput.hidden = false;
            cancelEdit.hidden = false;
        }
        else{
            for(let i=0; i<vesselInputs.length; i++){
                vesselInputs[i].disabled = true;
                vesselInputs[i].classList.add("inputDisabled");
                vesselInputs[i].classList.remove("inputActive");
            }
            
            addNew.hidden = false;
            serialDropdown.hidden = false;
            serialDropLabel.hidden = false;
            serialFieldLabel.hidden = true;
            serialNumInput.hidden = true;
            submitBtn.hidden = true;
            continueBtn.hidden = false;
            editBtn.hidden = false;
            cancelEdit.hidden = true;
        }

    }

    //Called if we have vessels but want to add a new one. Clears the inputs, enables them using previous func, and sets action to Add
    function clearInputs(e){
        e.preventDefault();
        console.log(vesselInputs[1]);
        for(let i=0; i<vesselInputs.length; i++){
            if(i != 0 && i != 1)
                vesselInputs[i].value = "";
        }
        toggleInputsAndButtons(true);
        action.value = "Add";
        result.innerHTML = "";
    }

    //Customer changed their mind? No problem. Undo what we did. 
    function CancelEdit(e){
        e.preventDefault();
        checkVessels(); 
    }
</script>