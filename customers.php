<?php
    include('includes/header.php');
    include('model/temp_model.php');

    $customers = getCustomers('');
    $search = "";

?>

    <div class="container justify-content-center h-100">
        <main>
            <div class="text-white text-center d-flex justify-content-between align-items-center">
                <div class="topDivs d-flex justify-content-start align-items-start mb-auto mt-2">
                    <button id="backButton" class="btn btn-info text-white font-weight-bold">< Back</button>
                </div>
                <h1  class="text-white text-center pt-3 pb-3"><b>Customers</b></h1>
                <div class="topDivs"></div>
            </div>
            <br>
            <div class="text-white text-center d-flex justify-content-center">
                <form method="post" class="d-flex justify-content-center align-items-center text-center">
                    <div class="mx-2">
                        <label for="searchName">Search: </label>
                        <input type="text" id="searchName" name="searchName" placeholder="Search Here" value="<?= $search ?>" class="text-primary rounded">  
                    </div>
                    <div>
                        <input id="searchBtn" type="submit" name="search" value="Search" class="bg-secondary text-white font-weight-bold rounded border border-0">
                    </div>
                </form>
            </div>
            <hr class="my-2 text-secondary opacity-100 border border-secondary border-1">
            <table class="table table-borderless table-striped text-white text-center align-text-center">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th class="border border-top-0 border-bottom-0 border-2">Customer Address</th>
                        <th>Phone Number</th>
                        <th class="border border-top-0 border-bottom-0 border-end-0 border-2">Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($customers as $record): ?>
                        <tr>
                            <td><a href="editCustomer.php?customerID=<?= $record["customerID"] ?>" class="text-white"><?= $record["customerName"] ?></a></td>
                            <td class="border border-top-0 border-bottom-0 border-2"><span><?= $record["customerAddress"] ?>, <?php if($record["customerAddress2"] != ""): ?> <?= $record["customerAddress2"] ?>, <?php endif; ?> <?= $record["customerCity"] ?>, <?= $record["customerState"] ?>, <?= $record["customerZipCode"] ?></span></td>
                            <td><span><?= $record["customerPhone"] ?></span></td>
                            <td class="border border-top-0 border-bottom-0 border-end-0 border-2"><span><?= $record["customerEmail"] ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-between">
                <div></div>
                <button id="createCustomerButton" class="btn btn-secondary text-white font-weight-bold">Create New Customer</button>
            </div>
        </main>

        <footer class="mt-3">
            <hr class="m-0 text-secondary opacity-100 border border-secondary border-1">
            <div class="p-1">
        </footer>
    </div> 
</body>
</html>   

<script>
    var backButton = document.querySelector(`#backButton`);
    var createCustomerButton = document.querySelector(`#createCustomerButton`);
    var searchField = document.querySelector(`#searchName`);
    var tableBody = document.querySelector(`tbody`);
    var searchTerm;
    var allCustomers = <?= json_encode($customers); ?>;

    searchField.addEventListener('input', (e)=> {
        searchTerm = e.target.value;
        FilterTable(searchTerm);
    })

    backButton.addEventListener(`click`, (e) => {
        e.preventDefault();
        window.location = 'home.php';
    })

    createCustomerButton.addEventListener(`click`, (e) => {
        e.preventDefault();
        window.location = 'editCustomer.php';
    })

    function FilterTable(srchtrm){
        let filteredCustomers = [];
        if(srchtrm == ""){
            resetTable(allCustomers);
        }
        
        for(let i=0; i<allCustomers.length;i++){
            if(allCustomers[i].customerName.toUpperCase().includes(srchtrm.toUpperCase()))
                filteredCustomers.push(allCustomers[i])
        }

        if(filteredCustomers.length > 0)
            resetTable(filteredCustomers);
        else{
            tableBody.innerHTML = `<tr class="tableRow col-12">
                                    <td></td>
                                    <td></td>
                                    <td>No Results</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    </tr>`; 
            }
    }

    function resetTable(newCustList){
        tableBody.innerHTML = ""

        for(let i = 0; i < newCustList.length; i++){
            tableBody.innerHTML += `
                <tr>
                    <td><a href="editCustomer.php?customerID=${newCustList[i].customerID}" class="text-white">${newCustList[i].customerName}</a></td>
                    <td class="border border-top-0 border-bottom-0 border-2"><span>${newCustList[i].customerAddress}, ${newCustList[i].customerCity}, ${newCustList[i].customerState}, ${newCustList[i].customerZip}</span></td>
                    <td><span>${newCustList[i].customerPhone}</span></td>
                    <td class="border border-top-0 border-bottom-0 border-end-0 border-2"><span>${newCustList[i].customerEmail}</span></td>
                </tr>
            `;
        }
    }
</script>