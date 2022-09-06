<?php
   //include('includes/header.php');

    //Check if session variable 'userID' is set
    // if(!isset($_SESSION["userID"])){
    //     //Redirect to event search page
    //     header("Location: home.php");
    // }

   
?>
<head>
  <title>LRSE Service Manager</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/custom.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap');
    hr{
      height: 2px;
    }
    body, html{
      font-family: 'Lato' !important;
    }
  </style>

</head>
<body class="bg-primary h-100">
<div class="container d-flex flex-column justify-content-between h-100">

    <nav class="navbar navbar-inverse container p-1">
      <div class="container-fluid justify-content-between bg-primary">
        <div class="navbar-header">
          <!-- <span class="navbar-brand"></span> -->
          <img src="images/Capstone Header Pic.png" height="50" width="100"/>
        </div>
        <div>
          <a href="home.php" class="text-white m-2">Home</a>
          <a href="logout.php" class="text-white m-2">Logout</a>
        </div>
      </div>
    </nav>
    <hr class="m-0 text-secondary opacity-100 border border-secondary border-1">
    <div id="mainDiv" class="container justify-content-center h-100">

           
            <div class="text-white text-center">
                <h3><b>ERROR</b></h3>
            </div>
            <!-- <div class="error">?= $invalid?></div> This is where the error is displayed if the credentials don't match. -->
            <div class="row text-white justify-content-center ">
                <h4 class="col-sm-3 col-md-2 col-lg-2 text-end align-middle">Unable to Connect to Server.</h4>
            </div>
            <div class="row text-white justify-content-center">
                <h4 class="col-sm-3 col-md-2 col-lg-2 text-end align-text-top">Please try again later.</h4>
            </div> 
    </div>
    <?php include('includes/footer.php'); ?>