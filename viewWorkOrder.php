<?php
    include('includes/header.php');
    include('model/model_work_order.php');

    $feedback = "";

    //check for Get/Set. If there is no work order number, routes back to home page. Otherwise, if the Stage Key was changed, update the key. 
    if(!isset($_GET["woNumber"])){
        header("Location: home.php");
    }

    if(filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST' && isset($_POST["stageKey"])){
        $result = updateStageKey($_GET["woNumber"], $_POST["stageKey"]);
        if($_POST["home"] == "true"){
            header("Location: home.php");
        }
        if($result == true){
            $feedback = "&#9989; Stage Key Updated &#9989;";
        }
    }

    //Retrieve all relevant details from the database tables. 
    $stageKeys = getStageKeys();
    $woDetials = getWorkOrder($_GET["woNumber"]);

    $customerDetails = getCutomer($woDetials["customerID"]);
    $vesselDetails = getVessel($woDetials["vesselID"]);
    $vesselManufacture = getVesselMenufacture($vesselDetails["vesselModel"]);

    //WOs can have more than one line item. Loop through to grab relevant fields. 
    $woItems = getItems($woDetials["woNum"]);
    for($i=0; $i < count($woItems); $i++){
        $temp = getProduct($woItems[$i]["productID"]);

        $woItems[$i]["productName"] = $temp["productName"];
        $woItems[$i]["productSerialNum"] = $temp["productSerialNum"];
        $woItems[$i]["productDescription"] = $temp["productDescription"];
    }
?>


    <div class="container justify-content-center h-100">
        <div class="text-white text-center d-flex justify-content-between align-items-center">
            <div class="topDivs d-flex justify-content-start align-items-start mb-auto mt-2">
                <button id="backButton" class="btn btn-info text-white font-weight-bold">< Back</button>
            </div>
            <div class="pt-3 pb-3 m-0 col-8">
                <h1><b>Customer Information</b></h1>
                <div id="results" class="text-center text-white"><?= $feedback; ?></div>
            </div>
            <div class="topDivs text-end">
                <div class="col-12 m-0"><span class="col-5">WO #: </span><span class="col-5"><?= $woDetials["woNum"] ?></span></div>
                <div class="col-12 m-0"><span class="col-5">Vessel Serial #: </span><span class="col-5"><?= $vesselDetails["vesselID"] ?></span></div>
                <div class="col-12 m-0"><span class="col-5">Order Date: </span><span class="col-5"><?= $woDetials["woDateCreated"] ?></span></div>
                <form method="post" id="formKey" class="d-flex justify-content-end align-items-center">
                    <input type="hidden" name="home" id="home" value="false">
                    <label for="stageKey">Stage: </label>
                    <select name="stageKey" id="stageKey" class="bg-primary border border-secondary text-white mx-2">
                        <!--Stage Keys are in a table rather than explicitly put here. Allows for adding removing stage keys as business requires. -->
                        <?php foreach($stageKeys as $key): ?>
                            <option value="<?= $key["stageKey"] ?>"
                            <?php if($woDetials["stageKey"] == $key["stageKey"]): ?> selected<?php endif; ?>>
                            <?= $key["stageKey"] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" id="updateStageKey" class="btn btn-sm btn-secondary text-white font-weight-bold">Update</button>
                </form>
            </div>
        </div>
        <!--Fill all the fields from the arrays we got at the top. All fields will be disabled  -->
        <form class="text-white">
            <div class="text-white d-flex justify-content-between">
                <div class="d-flex justify-content-center">
                    <label for="customerName" class="col-5 text-end">Customer Name</label>
                    <input type="text" name="customerName" value="<?= $customerDetails["customerName"] ?>" class="inputDisabled rounded col-8 mx-2" disabled>
                </div>
                <div class="d-flex justify-content-center">
                    <label for="customerEmail" class="col-5 text-end">Contact Email</label>
                    <input type="text" name="customerEmail" value="<?= $customerDetails["customerEmail"] ?>" class="inputDisabled rounded col-12 mx-2" disabled>
                </div>
                <div class="d-flex justify-content-center">
                    <label for="customerPhone" class="col-5 text-end">Phone Number</label>
                    <input type="text" name="customerPhone" value="<?= $customerDetails["customerPhone"] ?>" class="inputDisabled rounded col-8 mx-2" disabled>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <div class="text-white text-center col-6">
                    <h2 class="my-3">Customer Address</h2>

                    <div class="d-flex justify-content-center align-items-center sameAsDelivery">
                    </div>

                    <div class="d-flex justify-content-center my-2">
                        <label for="customerAddress" class="col-4 text-end mx-2">Street Address</label>
                        <input type="text" name="customerAddress" value="<?= $customerDetails["customerAddress"] ?>" class="addressInfo col-6 rounded inputDisabled" disabled>
                    </div>
                    <div class="d-flex justify-content-center my-2">
                        <label for="customerAddress2" class="col-4 text-end mx-2">Street Address 2</label>
                        <input type="text" name="customerAddress2" value="<?= $customerDetails["customerAddress2"] ?>" class="addressInfo col-6 rounded inputDisabled" disabled>
                    </div>
                    <div class="d-flex justify-content-center my-2">
                        <label for="customerCity" class="col-4 text-end mx-2">City</label>
                        <input type="text" name="customerCity" value="<?= $customerDetails["customerCity"] ?>" class="addressInfo col-6 rounded inputDisabled" disabled>
                    </div>
                    <div class="d-flex justify-content-center align-items-center my-2 col-12">
                        <div class="col-3">
                            <label for="customerState" class="col-4 text-end">State</label>
                            <select name="customerState" id="state" class="col-6 rounded inputDisabled" disabled>
                                <option value="<?= $customerDetails["customerState"] ?>"><?= $customerDetails["customerState"] ?></option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="customerZipCode" class="col-4 text-end">Zip Code</label>
                            <input type="text" name="customerZipCode" value="<?= $customerDetails["customerZipCode"] ?>" class="addressInfo col-6 rounded inputDisabled" disabled>
                        </div>
                    </div>
                </div>

                <div class="text-white text-center col-6">
                    <h2 class="my-3">Delivery Address</h2>

                    <div class="d-flex justify-content-center align-items-center sameAsDelivery my-2">
                        <div class="col-4"></div>
                        <div class="d-flex justify-content-start align-items-center col-6">
                            <input type="checkbox" <?php if($customerDetails["customerAddress"] == $customerDetails["customerDeliveryAddress"] && $customerDetails["customerAddress2"] == $customerDetails["customerDeliveryAddress2"] && $customerDetails["customerState"] == $customerDetails["customerDeliveryState"]): ?> checked <?php endif; ?> value="same" name="customerAddress" class="mx-2" disabled>
                            <label for="customerAddress">Same as Customer Address</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center my-2">
                        <label for="customerDeliveryAddress" class="col-4 text-end mx-2">Street Address</label>
                        <input type="text" name="customerDeliveryAddress" value="<?= $customerDetails["customerDeliveryAddress"] ?>" class="addressInfo col-6 rounded inputDisabled" disabled>
                    </div>
                    <div class="d-flex justify-content-center my-2">
                        <label for="customerDeliveryAddress2" class="col-4 text-end mx-2">Street Address 2</label>
                        <input type="text" name="customerDeliveryAddress2" value="<?= $customerDetails["customerDeliveryAddress2"] ?>"  class="addressInfo col-6 rounded inputDisabled" disabled>
                    </div>
                    <div class="d-flex justify-content-center my-2">
                        <label for="customerDeliveryCity" class="col-4 text-end mx-2">City</label>
                        <input type="text" name="customerDeliveryCity" value="<?= $customerDetails["customerDeliveryCity"] ?>" class="addressInfo col-6 rounded inputDisabled" disabled>
                    </div>
                    <div class="d-flex justify-content-center align-items-center my-2 col-12">
                        <div class="col-3">
                            <label for="customerDeliveryState" class="col-4 text-end">State</label>
                            <select name="customerDeliveryState" id="deliveryState" class="col-6 rounded inputDisabled" disabled>
                                <option value="<?= $customerDetails["customerDeliveryState"] ?>">RI</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="customerDeliveryZipCode" class="col-4 text-end">Zip Code</label>
                            <input type="text" name="customerZipCode" value="<?= $customerDetails["customerDeliveryZipCode"] ?>" class="addressInfo col-6 rounded inputDisabled" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <hr class="m-0 text-secondary opacity-100 border border-secondary border-1">
        <div class="text-white my-3">
            <h1 class="text-center"><b>Work Order Details</b></h1>
        
            <form>
                <div class="text-white d-flex justify-content-between my-2">
                    <div class="col-4">
                        <label for="workOrderPONumber" class="col-5 text-end">P.O. #</label>
                        <input type="text" name="workOrderPONumber" value="<?= $woDetials["poNum"] ?>" class="col-6 inputDisabled rounded" disabled>
                    </div>
                    <div class="col-4">
                        <label for="workOrderManufacture" class="col-5 text-end">Manufacture</label>
                        <input type="text" name="workOrderManufacture" value="<?= $vesselManufacture["vesselManufacturer"] ?>" class="col-6 inputDisabled rounded" disabled>
                    </div>
                    <div class="col-4">
                        <label for="workOrderEstematedCompletion" class="col-5 text-end">Est. Completion</label>
                        <input type="text" name="workOrderEstematedCompletion" value="<?= $woDetials["woEstCompletion"] ?>" class="col-6 inputDisabled rounded" disabled>
                    </div>
                </div>
                <div class="text-white d-flex justify-content-center my-2">
                    <div class="col-4">
                        <label for="workOrderTerms" class="col-5 text-end">Terms</label>
                        <input type="text" name="workOrderTerms" value="<?= $woDetials["terms"] ?>" class="col-6 inputDisabled rounded" disabled>
                    </div>
                    <div class="col-4">
                        <label for="workOrderRep" class="col-5 text-end">Rep</label>
                        <input type="text" name="workOrderRep" value="<?= $woDetials["rep"] ?>" class="col-6 inputDisabled rounded" disabled>
                    </div>
                    <div class="col-4">
                        <label for="workOrderWritenBy" class="col-5 text-end">Written By</label>
                        <input type="text" name="workOrderWritenBy" value="<?= $woDetials["writtenBy"] ?>" class="col-6 inputDisabled rounded" disabled>
                    </div>
                </div>

                <div>
                    <table class="table table-borderless table-striped text-white text-center align-text-center">
                        <thead>
                            <tr>
                                <th>Qty.</th>
                                <th>Serial Number</th>
                                <th>Item</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--Loop through WO line items in case there is more than one.  --> 
                            <?php foreach($woItems as $item): ?>
                                <tr>
                                    <td><input type="text" name="workOrderItemQuantity" value="<?= $item["quantity"] ?>" class="itemDisabled rounded" disabled></td>
                                    <td><input type="text" name="workOrderItemSerialNumber" value="<?= $item["productSerialNum"] ?>" class="itemDisabled rounded" disabled></td>
                                    <td><input type="text" name="workOrderItemName" value="<?= $item["productName"] ?>" class="itemDisabled rounded" disabled></td>
                                    <td><input type="text" name="workOrderItemDescription" value="<?= $item["productDescription"] ?>" class="itemDisabled rounded" disabled></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </form>
            <!-- Buttons for routing to pages associated with the work order.  -->
            <div class="d-flex justify-content-around col-12 mx-auto">
                <div class="text-center col-3">
                    <p>QC Checklist</p>
                    <button id="qualityContorleButton" class="btn btn-secondary text-white font-weight-bold mb-2 mx-auto">View/Edit</button>
                </div>
                <div class="text-center col-3">
                    <p>Parts Checklist</p>
                    <button id="partsChecklistButton" class="btn btn-secondary text-white font-weight-bold mb-2 mx-auto">View/Edit</button>
                </div>
                <div class="text-center col-3">
                    <p>Inspection Worksheet</p>
                    <button id="inspectionWorksheetButton" class="btn btn-secondary text-white font-weight-bold mb-2 mx-auto">View/Edit</button>
                </div>
            </div>
        </div>
        <footer>
            <hr class="m-0 text-secondary opacity-100 border border-secondary border-1">
            <div class="p-1">
        </footer>
    </div>
</body>
</html>

<script>
    //Query Selector Section **********************************************************************************************************************************
    var backButton = document.querySelector(`#backButton`);
    var qualityContorleButton = document.querySelector(`#qualityContorleButton`);
    var partsChecklistButton = document.querySelector(`#partsChecklistButton`);
    var inspectionWorksheetButton = document.querySelector(`#inspectionWorksheetButton`);
    var inspectionCertificateButton = document.querySelector(`#inspectionCertificateButton`);
    var stageKey = document.querySelector(`#stageKey`);
    var results = document.querySelector(`#results`);
    var form = document.querySelector(`#formKey`);
// End Query Selector Section **********************************************************************************************************************************

//Event Listeners Section **************************************************************************************************************************************
//All Buttons simply route to appropriate page with the woNum as a URL parameter. 
    backButton.addEventListener(`click`, (e) => {
        if(stageKey.value != "<?= $woDetials["stageKey"] ?>"){
            if(confirm("Would you like to save the stage key?")){ //In case they changed the stage key and hit back without clicking update. 
                document.getElementById(`home`).value = "true";
                //Submit form
                form.submit();
            }
            else{
                window.location = 'home.php';
            }
        }
        else{
            window.location = 'home.php';
        }
    })

    qualityContorleButton.addEventListener(`click`, (e) => {
        window.location = 'checklist.php?woNumber=<?= $_GET["woNumber"]; ?>';
    })

    partsChecklistButton.addEventListener(`click`, (e) => {
        window.location = 'partsList.php?woNumber=<?= $_GET["woNumber"]; ?>';
    })

    inspectionWorksheetButton.addEventListener(`click`, (e) => {
        window.location = 'inspectionSheet.php?woNumber=<?= $_GET["woNumber"]; ?>';
    })

    // inspectionCertificateButton.addEventListener(`click`, (e) => {
    //     window.location = 'inspectionCert.php?woNumber=<?= $_GET["woNumber"]; ?>';
    // })
//End Event Listeners Section **************************************************************************************************************************************

</script>