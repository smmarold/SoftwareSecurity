<?php    
    include('includes/header.php');
    include('model/model_customer.php');
    include('includes/functions.php');
    include('model/model_change_log.php');

    //Create new intance of Customer for storing values and calling methods. 
    $customer = new Customer();
    $canEdit = true; //Determine if we should enable inputs or not
    $action = "Add";
    $id = 0;
    $feedback = "";

    //POST Section ******************************************************************************************************************************
    if(isPostRequest()){ 
        $canEdit = false;
        $action = $_POST["action"];
        $customer->customerID = $_POST["customerID"];
        $customer->customerName = ucfirst($_POST["customerName"]);
        $customer->customerAddress = ucfirst($_POST["customerAddress"]);
        $customer->customerAddress2 = ucfirst($_POST["customerAddress2"]);
        $customer->customerCity = ucfirst($_POST["customerCity"]);
        $customer->customerState = $_POST["customerState"];
        $customer->customerZipCode = $_POST["customerZipCode"];
        $customer->customerDeliveryAddress = ucfirst($_POST["customerDeliveryAddress"]);
        $customer->customerDeliveryAddress2 = ucfirst($_POST["customerDeliveryAddress2"]);
        $customer->customerDeliveryCity = ucfirst($_POST["customerDeliveryCity"]);
        $customer->customerDeliveryState = $_POST["customerDeliveryState"];
        $customer->customerDeliveryZipCode = $_POST["customerDeliveryZipCode"];
        $customer->customerEmail = $_POST["customerEmail"];
        $customer->customerPhone = $_POST["customerPhone"];

        //Determin if Adding new or Editing, call appropriately. Update Changelog
        if($action == "Add"){
            $customer->customerID =  $customer->addCustomer();
            $result = "Customer Added";
        }
        elseif($action == "Edit"){
            $result = $customer->updateCustomer();
        }

        if($result != ""){
            $feedback =  "&#9989; " . $result . " &#9989;";
        }
    }//End POST Section, Begin GET ******************************************************************************************************************************
    elseif(isGetRequest()){
        $canEdit = false;
        $id = $_GET["customerID"];
        $customer = (object)$customer->getCustomers($id)[0]; //getCustomer returns Associative Array, so cast as object and store. 
    }
?>

    <div onload="checkEdit();" class="container justify-content-center h-100">
        <div class="text-white text-center d-flex justify-content-between align-items-center">
            <div class="topDivs d-flex justify-content-start align-items-start mb-auto mt-2">
                <button id="backButton" class="btn btn-info text-white font-weight-bold">< Back</button>
            </div>
            <h1  class="text-white text-center pt-3 pb-3"><b>Customer Information</b></h1>
            <div class="topDivs"></div>
        </div>
        <form action="editCustomer.php" method="post">
        <!-- Build Customer Information Section-->
            <div class="text-white d-flex justify-content-around">
                <input type="hidden" id="hiddenAction" name="action" value="Add"/>
                <input type="hidden" id="customerID" name="customerID" value="<?= $customer->customerID; ?>"/>
                <div class="d-flex justify-content-center">
                    <label for="customerName" class="col-5 text-end">Customer Name</label>
                    <input type="text" name="customerName" class="col-6 rounded mx-2" value="<?= $customer->customerName; ?>" maxlength="50" required placeholder="Ex. LRSE"/>
                </div>
                <div class="d-flex justify-content-center mx-2">
                    <label for="customerEmail" class="col-5 text-end">Contact Email</label>
                    <input type="text" name="customerEmail" class="col-12 rounded mx-2" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" oninvalid="this.setCustomValidity('Please enter valid email address')" value="<?= $customer->customerEmail; ?>" maxlength="50" required placeholder="Ex. LRSE@email.com"/>
                </div>
                <div class="d-flex justify-content-center">
                    <label for="customerPhone" class="col-5 text-end">Phone Number</label>
                    <input type="number" name="customerPhone" class="col-6 rounded mx-2" value="<?= $customer->customerPhone; ?>" max="999999999999" required placeholder="Ex. 0001112222"/>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <div class="text-white text-center col-6">
                    <h2 class="my-3">Customer Address</h2>

                    <div class="d-flex justify-content-center align-items-center sameAsDelivery">
                    </div>
                    <div class="d-flex justify-content-center my-2">
                        <label for="customerAddress" class="col-4 text-end mx-2">Street Address</label>
                        <input type="text" class="addressInfo col-6 rounded" name="customerAddress" value="<?= $customer->customerAddress; ?>" maxlength="150" required placeholder="Ex. 123 My Street"/>
                    </div>
                    <div class="d-flex justify-content-center my-2">
                        <label for="customerAddress2" class="col-4 text-end mx-2">Street Address 2</label>
                        <input type="text" class="addressInfo col-6 rounded" name="customerAddress2" value="<?= $customer->customerAddress2; ?>" maxlength="150" placeholder="Ex. Apt 12"/>
                    </div>
                    <div class="d-flex justify-content-center my-2">
                        <label for="customerCity" class="col-4 text-end mx-2">City</label>
                        <input type="text" class="addressInfo col-6 rounded" name="customerCity" value="<?= $customer->customerCity; ?>" maxlength="50" required placeholder="Ex. Tiverton"/>
                    </div>
                    <div class="d-flex justify-content-center align-items-center my-2 col-12">
                        <div class="col-6">
                            <label for="customerState" class="col-6 text-end mx-2">State</label>
                            <select name="customerState" id="customerState" class="col-3 rounded" required>
                                <!-- <option value="none"></option> -->
                                <?php fillStateDropdown(strtoupper($customer->customerState)); ?>
                            </select>
                        </div>
                        <div class="col-5">
                            <label for="customerZipCode" class="col-4 text-end mx-2">Zip Code</label>
                            <input type="text" class="addressInfo col-6 rounded" name="customerZipCode" value="<?= $customer->customerZipCode; ?>" maxlength="10" required placeholder="Ex. 12345"/>
                        </div>
                    </div>
                </div>

                <div class="text-white text-center col-6">
                    <h2 class="my-3">Delivery Address</h2>
                    <!-- Delivery Address is not optional, but includes a checkbox for using the same as customer address--> 
                    <div class="d-flex justify-content-center align-items-center sameAsDelivery my-2">
                        <div class="col-4"></div>
                        <div class="d-flex justify-content-start align-items-center col-6">
                            <input type="checkbox" <?php if($customer->customerAddress == $customer->customerDeliveryAddress && $customer->customerAddress2 == $customer->customerDeliveryAddress2 && $customer->customerState == $customer->customerDeliveryState && $customer->customerAddress != ""): ?> checked <?php endif; ?> value="true" id="sameAsAddress" class="mx-2"/>
                            <label for="sameAsAddress">Same as Customer Address</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center my-2">
                        <label for="customerDeliveryAddress" class="col-4 text-end mx-2">Street Address</label>
                        <input type="text" class="deliveryInfo col-6 rounded" name="customerDeliveryAddress" value="<?= $customer->customerDeliveryAddress; ?>" maxlength="150" required/>
                    </div>
                    <div class="d-flex justify-content-center my-2">
                        <label for="customerDeliveryAddress2" class="col-4 text-end mx-2">Street Address 2</label>
                        <input type="text" class="deliveryInfo col-6 rounded" name="customerDeliveryAddress2" value="<?= $customer->customerDeliveryAddress2; ?>" maxlength="150"/>
                    </div>
                    <div class="d-flex justify-content-center my-2">
                        <label for="customerDeliveryCity" class="col-4 text-end mx-2">City</label>
                        <input type="text" class="deliveryInfo col-6 rounded" name="customerDeliveryCity" value="<?= $customer->customerDeliveryCity; ?>" maxlength="50" required/>
                    </div>
                    <div class="d-flex justify-content-center align-items-center my-2 col-12">
                        <div class="col-6">
                            <label for="customerDeliveryState" class="col-6 text-end mx-2">State</label>
                            <select name="customerDeliveryState" id="customerDeliveryState" class="col-3 rounded" required>
                                <!-- <option value="na"></option> -->
                                <?php fillStateDropdown(strtoupper($customer->customerDeliveryState)); ?>
                            </select>
                        </div>
                        <div class="col-5">
                            <label for="customerDeliveryZipCode" class="col-4 text-end mx-2">Zip Code</label>
                            <input type="text" class="deliveryInfo col-6 rounded" name="customerDeliveryZipCode" value="<?= $customer->customerDeliveryZipCode; ?>" maxlength="10" required/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center my-3">
                <button id="cancelEdit" class="btn btn-info text-white font-weight-bold mx-2" hidden>Cancel</button>
                <button type="submit" id="submitBtn" name="submitBtn" class="btn btn-secondary text-white font-weight-bold">Add Customer</button>
            </div>
        </form>
        <?php if(!$canEdit){ ?>
            <div class="d-flex justify-content-center my-3">
                <button id="editCustomer" class="btn btn-secondary text-white font-weight-bold mx-2">Edit Customer</button>
                <button id="goToVessel" class="btn btn-secondary text-white font-weight-bold mx-2">Go To Vessel</button>
            </div>
        <?php } ?>
        <div id="results" class="text-center text-white"><?= $feedback; ?></div>
    </div>
    <footer>
        <hr class="m-0 text-secondary opacity-100 border border-secondary border-1">
        <div class="p-1">
    </footer>
</body>
</html>

<script>
    //Query Selectors ******************************************************************************************************
    var backButton = document.querySelector(`#backButton`);
    var submitBtn = document.querySelector(`#submitBtn`);
    var inputs = document.querySelectorAll('input');
    var deliveryCheckbox = document.querySelector('#sameAsAddress');
    var deliveryInputs = document.querySelectorAll('.deliveryInfo');
    var addressInputs = document.querySelectorAll('.addressInfo');
    var dropDowns = document.querySelectorAll('select');
    var customerID = document.querySelector('#customerID');
    var cancelEditBtn = document.querySelector('#cancelEdit');
    //End Query Selectors ***************************************************************************************************
    
    //Initialize/Declare Vars
    var cancelClicked = false;
    var custState;
    var delState;
    var storeDeliveryInputs = [];
    var editButton;
    var action;
    var goToVessel;
    

    //When the "same as customer address" box is checked, grab the current values of the deliver address (in case they change their mind)
    //then set the values of delivery address to the customer address. If UNCHECKED, the delivery address reverts to what it was before the
    // check.
    deliveryCheckbox.addEventListener('change', () => {
        if(deliveryCheckbox.checked){
            for(let i = 0; i<deliveryInputs.length; i++){
                storeDeliveryInputs[i] = deliveryInputs[i].value;
                deliveryInputs[i].value = addressInputs[i].value;
                deliveryInputs[i].disabled = true;
                deliveryInputs[i].classList.add("inputDisabled");
                deliveryInputs[i].classList.remove("inputActive");
            }
            delState = dropDowns[1].value;
            dropDowns[1].value = dropDowns[0].value;
            dropDowns[1].disabled = true;
            dropDowns[1].classList.add("inputDisabled");
            dropDowns[1].classList.remove("inputActive");
        }
        else if(deliveryCheckbox.checked == false && storeDeliveryInputs != ""){
            for(let i = 0; i<deliveryInputs.length; i++){
                deliveryInputs[i].value = storeDeliveryInputs[i];
                deliveryInputs[i].disabled = false;
                deliveryInputs[i].classList.remove("inputDisabled");
                deliveryInputs[i].classList.add("inputActive");
            }
            dropDowns[1].value = delState;
            dropDowns[1].disabled = false;
            dropDowns[1].classList.remove("inputDisabled");
            dropDowns[1].classList.add("inputActive");
        }
    })
    
    //Go back. 
    backButton.addEventListener(`click`, (e) => {
        window.location = 'customers.php';
    })

    //Cancel Edit discards any changes made and reverts the form to the disabled 'informational' form. Hides and shows appropriate buttons as well. 
    cancelEditBtn.addEventListener('click', (e) => {
        e.preventDefault();
        submitBtn.hidden = true;
        goToVessel.hidden = false;
        editButton.hidden = false;
        for(let i = 0; i<dropDowns.length; i++)
            dropDowns[i].disabled = true;
        for(let i = 0; i<inputs.length; i++)
            inputs[i].disabled = true;
        checkEdit();
        cancelEditBtn.hidden = true;

    })

    //On Page Load, we check if canEdit is true or false. 
    //If false, we are 'viewing' a customer, and disable fields.
    //If true, there are no customers, or we are adding a new customer. Enable fields and buttons
    function checkEdit(){
        var canEdit = <?= json_encode($canEdit); ?>;
        //console.log(canEdit);
        if(!canEdit){
            submitBtn.hidden = true;
            cancelEditBtn.hidden = true;
            enableEditButton();
            for(let i = 0; i<dropDowns.length; i++)
            {
                dropDowns[i].disabled = true;
                dropDowns[i].classList.add("inputDisabled");
                dropDowns[i].classList.remove("inputActive");
            }
            for(let i = 0; i<inputs.length; i++)
            {
                inputs[i].disabled = true;
                inputs[i].classList.add("inputDisabled");
                inputs[i].classList.remove("inputActive");
            }
        }
    }

    //if Can Edit is false, this function is called, enabling the "edit" button and hiding the add/update button until
    //Edit is clicked. 
    function enableEditButton(){
        action = document.querySelector('#hiddenAction');
        editButton = document.querySelector('#editCustomer');
        goToVessel = document.querySelector('#goToVessel');

        editButton.addEventListener('click', ()=>{
            action.value = "Edit";
            submitBtn.textContent = "Update Customer";
            submitBtn.hidden = false;
            editButton.hidden = true;
            goToVessel.hidden = true;
            for(let i = 0; i<dropDowns.length; i++)
            {
                dropDowns[i].disabled = false;
                dropDowns[i].classList.add("inputActive");
                dropDowns[i].classList.remove("inputDisabled");
            }
            for(let i = 0; i<inputs.length; i++)
            {
                inputs[i].disabled = false;
                inputs[i].classList.add("inputActive");
                inputs[i].classList.remove("inputDisabled");
            }
            if(deliveryCheckbox.checked){
                for(let i = 0; i<deliveryInputs.length; i++){
                    storeDeliveryInputs[i] = deliveryInputs[i].value;
                    deliveryInputs[i].value = addressInputs[i].value;
                    deliveryInputs[i].disabled = true;
                    deliveryInputs[i].classList.add("inputDisabled");
                    deliveryInputs[i].classList.remove("inputActive");
                }
                delState = dropDowns[1].value;
                dropDowns[1].value = dropDowns[0].value;
                dropDowns[1].disabled = true;
                dropDowns[1].classList.add("inputDisabled");
                dropDowns[1].classList.remove("inputActive");
            }
            else if(deliveryCheckbox.checked == false && storeDeliveryInputs != ""){
                for(let i = 0; i<deliveryInputs.length; i++){
                    deliveryInputs[i].value = storeDeliveryInputs[i];
                    deliveryInputs[i].disabled = false;
                    deliveryInputs[i].classList.remove("inputDisabled");
                    deliveryInputs[i].classList.add("inputActive");
                }
                dropDowns[1].value = delState;
                dropDowns[1].disabled = false;
                dropDowns[1].classList.remove("inputDisabled");
                dropDowns[1].classList.add("inputActive");
            }
            cancelEditBtn.hidden = false;
        })

        goToVessel.addEventListener('click', ()=>{
            window.location = 'editVessels.php?customerID=<?= $customer->customerID; ?>';
        })
    }

</script>