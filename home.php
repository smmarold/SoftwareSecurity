<?php
    include('includes/header.php');
    include('model/model_work_order.php');

    if(isset($_GET["delete"]))
    {
        $result = deleteWO($_GET["delete"]); //Able to Delete WO Num. 
    }

    $workOrders = searchWorkOrders(""); //Get all work orders from database. 
    $stageKeys = getStageKeys(); 
?>
    <!-- Header and Search functionality. -->
    <div class="container justify-content-center h-100">
        <div>
            <h1 class="text-white text-center pt-3 pb-3"><b>Work Orders</b></h1>
            <form method="post">
                <div class="searchBar text-white text-center d-flex justify-content-center">
                    <div>
                        <label for="searchField" class="">Search: </label>
                        <input id="searchField" type="text" name="searchField" placeholder="Search Here" class="text-primary rounded">  
                    </div>
                    <div class="mx-3">
                        <label for="stageKey" class="marginL">Filter By Stage: </label>
                        <select name="stageKey" id="stageKey" class="text-primary rounded">
                            <option value="All">All</option>
                            <?php foreach($stageKeys as $key){ ?> <!--Dynamically create dropdown in case clients changed Stage Keys at a later date -->
                                <option value="<?= $key["stageKey"]; ?>"><?= $key["stageKey"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <input id="searchBtn" type="submit" name="search" value="Search" class="bg-secondary text-white font-weight-bold rounded border border-0">
                    </div>
                </div>
            </form>
            <hr class="my-2 text-secondary opacity-100 border border-secondary border-1">
            <!-- Create Work Order Table. WO Num is clickable to go to view page for specific WO. -->
            <table class="table table-borderless table-striped text-white text-center align-text-center">
                <thead>
                    <tr>
                        <th>WO Number</th>
                        <th class="border border-top-0 border-bottom-0 border-2">Customer Name</th>
                        <th>Manufacturer</th>
                        <th class="border border-top-0 border-bottom-0 border-2">Estimated Completion Date</th>
                        <th>Stage Key</th>
                        <?php if($_SESSION["accountType"] == 'Admin'): ?><th class="border border-top-0 border-bottom-0 border-end-0 border-2"></th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <!--Create each record in table from DB. Last column is delete button for deleting that record.  -->
                    <?php foreach($workOrders as $record){ ?>
                    <tr class="text-white">
                        <td><a href="viewWorkOrder.php?woNumber=<?= $record["woNum"] ?>" class="text-white"><?= $record["woNum"] ?></a></td>
                        <td class="border border-top-0 border-bottom-0 border-2"><?= $record["customerName"] ?></td>
                        <td><?= $record["vesselManufacturer"] ?></td>
                        <td class="border border-top-0 border-bottom-0 border-2"><?= $record["woEstCompletion"] ?></td>
                        <td><?= $record["stageKey"] ?></td>
                        <?php if($_SESSION["accountType"] == 'Admin'): ?><td class="border border-top-0 border-bottom-0 border-end-0 border-2"><a class="deleteWO text-white" href="#" woNum="<?= $record["woNum"] ?>"><img src="images/delete.png" height="25" width="25"/></a></td><?php endif; ?>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between">
            <div></div>
            <button  id="creatWorkOrder" class="btn btn-secondary text-white font-weight-bold">Create New Work Order</button>
        </div>

        <footer class="mt-3">
            <hr class="m-0 text-secondary opacity-100 border border-secondary border-1">
            <div class="p-1">
        </footer>
    </div>
</body>
</html>

<script>
    // Query Selectors ******************************************************************************************************************
    var searchBtn = document.querySelector('#searchBtn');
    var searchField = document.querySelector('#searchField');
    var stageKeyDropdown = document.querySelector('#stageKey');
    var tableBody = document.querySelector('tbody');
    var deleteWO = document.querySelectorAll('.deleteWO');
    // End Query Selectors ***************************************************************************************************************

    //Global Vars for search functionality.
    var workOrderList = <?= json_encode($workOrders); ?>; //Get the original work order list so we can reset table if needed. 
    var searchTerm;
    var addStageFilter = false; //If stage key drop is not on default, set to true so we filter by stage when searching.
    
    //Event Listeners ************************************************************************************************************************
    //While searching, we are constantly destroying and rebuilding the table. This function adds the delete button back at the end of each row.
    function addNewEventListeners(){
        deleteWO = document.querySelectorAll('.deleteWO');
        for(let i=0; i<deleteWO.length; i++){
            deleteWO[i].addEventListener('click', (e) => {
                e.preventDefault();
                if(confirm(`Would you like to delete work order ${deleteWO[i].getAttribute("woNum")}?`)){
                    window.location = `home.php?delete=${deleteWO[i].getAttribute("woNum")}`;
                }
            })
        }
    }

    searchBtn.addEventListener('click', (e) => {
        e.preventDefault();
        searchTerm = searchField.value;
        filterTable(searchTerm);
    })
    //console.log(workOrderList);

    //The search input itself will call filter table each time a letter is input. 
    searchField.addEventListener('input', (e)=> {
        searchTerm = e.target.value;
        filterTable(searchTerm);
    })

    stageKeyDropdown.addEventListener('change', (e)=>{
        searchTerm = searchField.value;
        if(stageKeyDropdown.value == "All")
            addStageFilter = false;
        else
            addStageFilter = true;
        filterTable(searchTerm)
    })

    //If we want a new work order, this button goes to the customer selection page to start the process. 
    var createWorkOrder = document.querySelector(`button`);
    createWorkOrder.addEventListener(`click`, (e) => {
        window.location = 'customers.php';
    })

    //End Event Listeners ************************************************************************************************************************

    //Filter the table based on the search term called by the input field. if there is no search term and no stage key
    // filter is selected, then reset the table using the originally stored work order list. 
    function filterTable(srchTerm){
        let filteredWorkOrders = [];
        if(srchTerm == "" && !addStageFilter){
            resetTable(workOrderList); 
            return;
        }
        else if(srchTerm == "" && addStageFilter){
            filteredWorkOrders = FilterByStage(workOrderList); //if search field is empty, but stage is selected, just filter by stage. 
            if(filteredWorkOrders.length > 0)
                resetTable(filteredWorkOrders);
            else //If there are no results, build a quick 'No Results' Table for customer feedback.
                tableBody.innerHTML = `<tr class="tableRow col-12">
                                    <td></td>
                                    <td></td>
                                    <td>No Results</td> 
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    </tr>`;
            return;
        }
        //Filter the list based on search terms. Can search customer name, wo num, or vessel manufacturer
        for(let i=0; i<workOrderList.length; i++){
            if(workOrderList[i].customerName.toUpperCase().includes(srchTerm.toUpperCase())){
                filteredWorkOrders.push(workOrderList[i]);
                // console.log('Pushed:' + workOrderList[i].customerName)
            }
            else if(workOrderList[i].woNum.toString().includes(srchTerm.toUpperCase())){
                filteredWorkOrders.push(workOrderList[i]);
                // console.log('Pushed:' + workOrderList[i].woNum.toString());

            }
            else if(workOrderList[i].vesselManufacturer.toUpperCase().includes(srchTerm.toUpperCase())){
                filteredWorkOrders.push(workOrderList[i]);
                // console.log('Pushed:' + workOrderList[i].vesselManufacturer);

            }
        }
        //Now that the serach term is filtered, further filter by stage key if it's selected. allows for combining search field and stage dropdown in search
        if(addStageFilter){
            filteredWorkOrders =  FilterByStage(filteredWorkOrders);
            // console.log('Got Here');
        }

        if(filteredWorkOrders.length >0)
            resetTable(filteredWorkOrders);
        else //Again, no results means creating a 'No Results' table.
            tableBody.innerHTML = `<tr class="tableRow col-12">
                                    <td></td>
                                    <td></td>
                                    <td>No Results</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    </tr>`; 
    }

    //Rebuild the table with the passed list of objects, can be filtered or original based on where it's called from. 
    function resetTable(objArray){
        tableBody.innerHTML = ""; //Clear the table before rebuilding

        for(let j=0; j<objArray.length; j++){
            tableBody.innerHTML += `
                <tr class="tableRow col-12"> 
                    <td><a href="viewWorkOrder.php?woNumber=${objArray[j].woNum}" class="text-white">${objArray[j].woNum}</a></td>
                    <td class="border border-top-0 border-bottom-0 border-2">${objArray[j].customerName}</td>
                    <td>${objArray[j].vesselManufacturer}</td>
                    <td class="border border-top-0 border-bottom-0 border-2">${objArray[j].woEstCompletion}</td>
                    <td>${objArray[j].stageKey}</td>
                    <td class="border border-top-0 border-bottom-0 border-end-0 border-2"><a class="deleteWO text-white" href="#" woNum="${objArray[j].woNum}"><img src="images/delete.png" height="25" width="25"/></a></td>
                </tr>`;
        }
        addNewEventListeners(); //Add those delete buttons back in after rebuilding
    }

    //The func for filtering by the Stage key dropdown. Returns the filtered list. 
    function FilterByStage(passedWorkOrders){
        let stageFilteredElements = []
        for(let i=0; i<passedWorkOrders.length; i++){
            if(passedWorkOrders[i].stageKey == stageKeyDropdown.value){
                stageFilteredElements.push(passedWorkOrders[i]);
            }
        }
        return stageFilteredElements;
    }
    addNewEventListeners();
</script>

