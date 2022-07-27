<?php
    include('includes/header.php');
    include('model/model_work_order.php');
    include('model/model_change_log.php');

    date_default_timezone_set('America/New_York');

    //Initialize Vars
    $customerID = $_GET["customerID"];
    $vesselID = $_GET["vesselID"];
    $customerName = getCustomerName($customerID);
    $woNumber = "";
    $poNumber = "";
    $woDate = date("Y-m-d");
    $estCompetion = "";
    $terms = "";
    $rep = "";
    $writenBy = "";
    $woItems = ["",""];

    $products = getProducts();

    if(!isset($_GET["customerID"])){//As usual, redirect home if no URL param exists
        header("Location: customers.php");
    }
    elseif(!isset($_GET["vesselID"])){
        header("Location: editVessels.php?customerID=" . $customerID);
    }
    //Begin POST Section *************************************************************************************************************************
    if(filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST'){
        $poNumber = $_POST["workOrderPONumber"];
        $woDate = $_POST["workOrderDate"];
        $estCompetion = $_POST["workOrderEstematedCompletion"];
        $terms = $_POST["workOrderTerms"];
        $rep = $_POST["workOrderRep"];
        $writenBy = $_POST["workOrderWritenBy"];

        $tempWoItems = array_values($_POST);
        for($i=6; $i < count($tempWoItems); $i++){
            $woItems[$i-6] = $tempWoItems[$i];
        }

        //Find Appropriate Product, store in WO Items 
        $found = false;
        $index = [0, 1];
        while($found == false && count($woItems) > $index[1]){
            if($products[$index[0]]["productSerialNum"] == $woItems[$index[1]]){
                $woItems[$index[1]] = $products[$index[0]]["productID"];
                $found = true;
            }
            else{
                $index[0]++;
            }

            if($found == true && count($woItems) > $index[1]){
                $index[1] += 2;
                $index[0] = 0;
                $found = false;
            }

            if($found == false && count($products) <= $index[0]){
                $index[1] += 2;
                $index[0] = 0;
            }
        }
        //Add new WO to DB
        $woNumber = addWorkOrder($customerID, $vesselID, $poNumber, $woDate, $estCompetion, $terms, $rep, $writenBy);
        //Add Wo Items to Database
        for($i=0; $i < count($woItems); $i+=2){
            $result = addWorkOrderItem($woNumber["woNum"], $woItems[$i], $woItems[$i+1]);
        }
        //Update Changelog
        if($result == true){
            addChange($_SESSION['userID'], date("Y-m-d"), $woNumber["woNum"], "Created new work order");
            header("Location: viewWorkOrder.php?woNumber=".$woNumber["woNum"]);
        }
    }
    //End POST Section *************************************************************************************************************************

?>
    <!-- Create WO Form, filling customer info from previous page. Disable Inputs for customer info -->
    <div class="container justify-content-center h-100">
        <div class="text-white text-center d-flex justify-content-between align-items-center">
            <div class="topDivs d-flex justify-content-start align-items-start mb-auto mt-2">
                    <button id="backButton" class="btn btn-info text-white font-weight-bold">< Back</button>
                </div>
            <h1 class="text-white text-center pt-3 pb-3"><b>Work Order Details</b></h1>
            <div class="topDivs"></div>
        </div>
        <form method="post">
            <div class="text-white d-flex justify-content-center my-3">
                <div class="col-4">
                    <label for="customerName" class="col-5 text-end">Customer Name</label>
                    <input type="text" name="customerName" class="rounded col-6 text-white inputDisabled" value="<?= $customerName["customerName"] ?>" disabled>
                </div>
                <div class="col-4">
                    <label for="vesselSerialNumber" class="col-5 text-end">Vessel Serial #</label>
                    <input type="text" name="vesselSerialNumber" class="rounded col-6 text-white inputDisabled" value="<?= $vesselID ?>" disabled>
                </div>
                <div class="col-4">
                    <label for="workOrderPONumber" class="col-5 text-end">P.O. #</label>
                    <input type="text" name="workOrderPONumber" class="rounded col-6 text-primary inputActive" value="<?= $poNumber ?>" maxlength="15">
                </div>
            </div>
            <div class="text-white d-flex justify-content-center my-3">
                <div class="col-4">
                    <label for="workOrderDate" class="col-5 text-end">Order Date</label>
                    <input type="date" name="workOrderDate" class="rounded col-6 text-primary inputActive" value="<?= $woDate ?>" min="<?= $woDate ?>">
                </div>
                <div class="col-4">
                    <label for="workOrderEstematedCompletion" class="col-5 text-end">Est. Completion</label>
                    <input type="date" name="workOrderEstematedCompletion" class="rounded col-6 text-primary inputActive" value="<?= $estCompetion ?>" required min="<?= $woDate ?>">
                </div>
                <div class="col-4">
                    <label for="workOrderTerms" class="col-5 text-end">Terms</label>
                    <input type="text" name="workOrderTerms" class="rounded col-6 text-primary inputActive" maxlength="25" value="<?= $terms ?>" maxlength="25">
                </div>
            </div>
            <div class="text-white d-flex justify-content-center my-3">
                <div class="col-5">
                    <label for="workOrderRep" class="col-5 text-end">Rep</label>
                    <input type="text" name="workOrderRep" class="rounded col-3 text-primary" maxlength="2" value="<?= $rep ?>">
                </div>
                <div class="col-5">
                    <label for="workOrderWritenBy" class="col-5 text-end">Written By</label>
                    <input type="text" name="workOrderWritenBy" class="rounded col-3 text-primary" maxlength="2" value="<?= $writenBy ?>">
                </div>
            </div>
        <!-- Line Item Section --> 
            <div>
                <table id="woItems" class="table table-borderless table-striped text-white text-center align-text-center">
                    <thead>
                        <tr>
                            <th><a href="#" id="newItemLink" class="text-white">+ Add New Line</a></th>
                            <th class="col-1">Qty.</th>
                            <th class="col-2">Serial Number</th>
                            <th class="col-3">Item</th>
                            <th class="col-5">Description</th>
                        </tr>
                    </thead>
                    <!-- Create Line Item Table-->
                    <tbody id="woItemsBody">
                        <?php for($i=0; $i < count($woItems); $i += 2): ?>
                            <tr id="item<?php echo $i/4 ?>">
                                <td><img src="images/delete.png" height="25" width="25" class="deleteItemLink"/></td>
                                <td><input type="number" class="inputActive rounded col-11" name="workOrderItemQuantity<?php echo $i/2 ?>" value="<?php echo $woItems[$i] ?>" required min="1"></td>
                                <td>
                                    <input list="itemSerialNumbers" name="itemSerialNumbers<?php echo $i/2 ?>" class="itemSerialNumbers text-primary rounded inputActive col-11" id="itemSerialNumbers<?php echo $i/2 ?>" value="<?= $woItems[$i+1] ?>" maxlength="25">
                                    <datalist id="itemSerialNumbers">
                                        <?php foreach($products as $record): ?>
                                            <option value="<?= $record["productSerialNum"]; ?>"></option>
                                        <?php endforeach ?>
                                    </datalist>
                                </td>
                                <td><input type="text" name="workOrderItemName<?php echo $i/2 ?>" class="itemName text-white rounded itemDisabled col-11" disabled></td>
                                <td><input type="text" name="workOrderItemDescription<?php echo $i/2 ?>" class="itemDesc text-white rounded itemDisabled col-11" disabled></td>
                            </tr>
                        <?php endfor ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center my-3">
                <button type="submit" id="saveButton" class="btn btn-secondary text-white font-weight-bold mx-2">Save</button>
            </div>
        </form>
        <p id="error" class="text-white text-center"></p>
    </div>
    <footer>
        <hr class="m-0 text-secondary opacity-100 border border-secondary border-1">
        <div class="p-1">
    </footer>
</body>
</html>

<script>
    //Query Selectors ***********************************************************************************************************************
    var backButton = document.querySelector(`#backButton`);
    var form = document.querySelector(`form`);
    var saveButton = document.querySelector(`#saveButton`);
    var newItemLink = document.querySelector(`#newItemLink`);
    var deleteItemLinks = document.querySelectorAll(`.deleteItemLink`);
    var woItemsBody = document.querySelector(`#woItemsBody`);
    var itemSearialNumbers = document.querySelectorAll(`.itemSerialNumbers`);
    var datalist = document.querySelectorAll(`datalist`);
    var itemName = document.querySelectorAll(`.itemName`);
    var itemDesc = document.querySelectorAll(`.itemDesc`);
    //End Query Selectors *********************************************************************************************************************

    //Store number of line items, and how many have been added. 
    var itemCount = deleteItemLinks.length - 1;
    var itemsAdded = deleteItemLinks.length;

    var products = <?php echo json_encode($products); ?>;

    //Back Button
    backButton.addEventListener(`click`, (e) => {
        e.preventDefault();
        window.location = 'editVessels.php';
    })

    //New Item Button Event Listener
    newItemLink.addEventListener(`click`, (e) => {
        e.preventDefault();
        itemsAdded++; //Track how many line items have been added 
        var newRow = document.createElement("tr"); //Create a new row in the table for the line item. 
        //Add Elements for line item to the row. 
        woItemsBody.insertBefore(newRow, woItemsBody.childNodes[0]);
        woItemsBody.childNodes[0].insertAdjacentHTML("beforeend", `<tr id="item${itemsAdded}">
                            <td><img src="images/delete.png" height="25" width="25" class="deleteItemLink"/></td>
                            <td><input type="number" class ="text-primary rounded inputActive col-11" name="workOrderItemQuantity${itemsAdded}" required  min="1"></td>
                            <td>
                                <input list="itemSerialNumbers" name="itemSerialNumbers${itemsAdded}" class="itemSerialNumbers text-primary rounded inputActive col-11" id="itemSerialNumbers${itemsAdded}" maxlength="25">
                                <datalist id="itemSerialNumbers">
                                    <?php foreach($products as $record): ?>
                                        <option value="<?= $record["productSerialNum"]; ?>">
                                    <?php endforeach ?>
                                </datalist>
                            </td>
                            <td><input type="text" name="workOrderItemName${itemsAdded}" class="itemName text-white rounded itemDisabled col-11" disabled></td>
                            <td><input type="text" name="workOrderItemDescription${itemsAdded}" class="itemDesc text-white rounded itemDisabled col-11" disabled></td>
                        </tr>`);
        deleteItemLinks = document.querySelectorAll(`.deleteItemLink`);
        itemCount = deleteItemLinks.length - 1;
        
        createDeleteEvent(0); 

        //refresh query selectors to include new line item
        itemSearialNumbers = document.querySelectorAll(`.itemSerialNumbers`);
        datalist = document.querySelectorAll(`datalist`);
        itemName = document.querySelectorAll(`.itemName`);
        itemDesc = document.querySelectorAll(`.itemDesc`);
        for(let i=0; i<itemSearialNumbers.length; i++){
            createDatalistEvent(i);
        }
    })

    //when a new line item is created, add an event listener to them to remove the line item from the table. 
    function createDeleteEvent(i){
        //Add click event listener to delete buttons
        deleteItemLinks[i].addEventListener(`click`, (e)=>{
            //Prevent the default action of button
            e.preventDefault();

            if(itemCount > 0){
                e.target.parentElement.parentElement.parentElement.removeChild(e.target.parentElement.parentElement);
            }
            else{
                e.target.parentElement.parentElement.parentElement.removeChild(e.target.parentElement.parentElement);
                itemsAdded++;
                woItemsBody.insertAdjacentHTML("beforeend", `<tr id="item${itemsAdded}">
                        <td><img src="images/delete.png" height="25" width="25" class="deleteItemLink"/></td>
                        <td><input type="number" class ="text-primary rounded inputActive col-11" name="workOrderItemQuantity${itemsAdded}" required  min="1"></td>
                        <td>
                            <input list="itemSerialNumbers" name="itemSerialNumbers${itemsAdded}" class="itemSerialNumbers text-primary rounded inputActive col-11" id="itemSerialNumbers${itemsAdded}" maxlength="25">
                            <datalist id="itemSerialNumbers">
                                <?php foreach($products as $record): ?>
                                    <option value="<?= $record["productSerialNum"]; ?>">
                                <?php endforeach ?>
                            </datalist>
                        </td>
                        <td><input type="text" name="workOrderItemName${itemsAdded}" class="itemName text-white rounded itemDisabled col-11" disabled></td>
                        <td><input type="text" name="workOrderItemDescription${itemsAdded}" class="itemDesc text-white rounded itemDisabled col-11" disabled></td>
                    </tr>`);

                deleteItemLinks = document.querySelectorAll(`.deleteItemLink`);
                itemCount = deleteItemLinks.length - 1;
                createDeleteEvent(0);

                itemSearialNumbers = document.querySelectorAll(`.itemSerialNumbers`);
                datalist = document.querySelectorAll(`datalist`);
                itemName = document.querySelectorAll(`.itemName`);
                itemDesc = document.querySelectorAll(`.itemDesc`);
                createDatalistEvent(0);
            }

            deleteItemLinks = document.querySelectorAll(`.deleteItemLink`);
            itemCount = deleteItemLinks.length - 1;

            itemSearialNumbers = document.querySelectorAll(`.itemSerialNumbers`);
            datalist = document.querySelectorAll(`datalist`);
            itemName = document.querySelectorAll(`.itemName`);
            itemDesc = document.querySelectorAll(`.itemDesc`);
        })
    }

    //Each line item has a datalist, so when a new line item is created, add this event listener to the datalist for searching and filtering products. 
    function createDatalistEvent(i){
        itemSearialNumbers[i].addEventListener(`change`, (e)=>{
            //Prevent the default action of button
            e.preventDefault();

            let found = false;
            let index = 0;

            const Value = itemSearialNumbers[i].value;

            if(!Value) return;

            const Text = document.querySelector('option[value="' + Value + '"]');

            const option =document.createElement("option");
            option.value=Value;
            option.text=Text;

            while(found == false && products.length > index){
                if(option.value == products[index].productSerialNum){
                    found = true;
                    itemName[i].value = products[index].productName;
                    itemDesc[i].value = products[index].productDescription;
                }
                else{
                    index++;
                }
            }

            if(found == false){
                itemName[i].value = "";
                itemDesc[i].value = "";
            }
        })
    }

    //Saves the work order
    saveButton.addEventListener(`click`, (e)=>{
        //Prevent the default action of button
        e.preventDefault();

        let items = [];
        let submit = true;
        let error = ["", ""];

        //Loop through the line items, make sure there are no duplicate serial numbers. 
        for(let i=0; i < itemSearialNumbers.length; i++){
            let found = false;
            for(let index=0; index < items.length; index++){
                if(itemSearialNumbers[i].value == items[index]){
                    found = true;
                }
            }

            if(found == true){
                error[0] = `&#10060; Items can not have the same serial number &#10060;`;
                submit = false;
            }
            else{
                items[items.length] = itemSearialNumbers[i].value;
            }
            //Serial Num validation, needs to be there. 
            if(itemName[i].value == "" || itemDesc[i].value == ""){
                error[1] = "&#10060; Item serial number must exist &#10060;";
                submit = false;
            }
        }
        //If we are submitting and the form validates, submit the form, otherwise, give feedback. 
        if(submit == true && form.checkValidity() == true){
            form.submit();
        }
        else{
            let errorMSG = document.querySelector(`#error`);
            errorMSG.innerHTML = `${error[0]}<br>${error[1]}`;
            form.reportValidity();
        }
    })

    //Loop through all delete links
    for(let i=0; i<deleteItemLinks.length; i++){
        createDeleteEvent(i);
        createDatalistEvent(i);
    }
</script>