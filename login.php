<?php
    include('includes/header.php');
    include('model/model_login.php');
    //Initialize variables
    $userName = "";
    $errorMsg = "";
    $result = [];

    //Check if session variable 'userID' is set
    if(isset($_SESSION["userID"])){
        //Redirect to event search page
        header("Location: home.php");
    }

    //Check if there is a POST request
    if(filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST'){
        $userName = strtolower(filter_input(INPUT_POST, "userName")); //Get user name form POST and make it lowercase
        $password = filter_input(INPUT_POST, "password");             //Get password from POST

        //Try to login
        $result = login($userName, $password);

        //Clear password variable
        $password = "000000000000000000";

        //Chack if login was successful
        if($result['userID'] != ""){
            //Put user id and account type in session
            $_SESSION['userID'] = $result['userID'];
            $_SESSION['accountType'] = $result['accountType'];
            
            //Redirect to home page page
            header("Location: home.php");
        }
        else{
            //Error message if login failed
            $errorMsg = "&#10060; Invalid Account Information &#10060;";
        }
    }
?>
    <div id="mainDiv" class="container justify-content-center h-100">
        <form method="post">
           
            <div class="text-white text-center pt-3 pb-3">
                <h3><b>Login</b></h3>
            </div>
            <!-- <div class="error">?= $invalid?></div> This is where the error is displayed if the credentials don't match. -->
            <div class="row text-white justify-content-center pb-3">
                <label for="userName" class="col-sm-3 col-md-2 col-lg-2 text-end align-middle">Username:</label>
                <input type="text" name="userName" value="admin" class="col-3 text-primary rounded">
            </div>
            <div class="row text-white justify-content-center">
                <label for="password" class="col-sm-3 col-md-2 col-lg-2 text-end align-text-top">Password:</label>
                <input type="password" name="password" value="admin" class="col-3 text-primary rounded">
            </div>
            <br>
            <div class="justify-content-center text-center">
                <div class="col1">&nbsp;</div>
                <div class="d-flex justify-content-center"><input type="submit" name="login" value="Login" class="btn btn-secondary text-white font-weight-bold w-2" style="width: 100px;"></div> 
            </div>
            <div class="text-center text-white my-2"><?= $errorMsg; ?></div>
            
        </form>
    </div>
    <?php include('includes/footer.php'); ?>