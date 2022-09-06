<?php
    include('model/model_validation.php');

    error_reporting(0); //Stop php from reporting errors

    session_start(); //Start a session on each page load. 

    $url = $_SERVER["REQUEST_URI"];

    $url = explode("/", $url);
    $url = explode("?", $url[count($url)-1]);

    //check if the session exists, if not, redirect to login page. 
    //It may be unnecessary to also check if the session logged in is false, but I felt it was safer to do so. 
    if((!isset($_SESSION['userID']) || !isset($_SESSION['accountType'])) && $url[0] != "login.php")
      header('Location: login.php');
    else if(isset($_SESSION['userID']) && $url[0] == "login.php")
      header('Location: home.php');

    //Check to make sure woNumber exists in database
    if(isset($_GET["woNumber"])){
      if(!validateWONum($_GET["woNumber"])){
        header('Location: home.php');
      }
    }

    //Check to make sure customerID exists in database
    if(isset($_GET["customerID"])){
      if(!validateCustomer($_GET["customerID"])){
        header('Location: customers.php');
      }
    }

    //Check to make sure vesselID exists in database
    if(isset($_GET["vesselID"])){
      if(!validateVessle($_GET["vesselID"])){
        header('Location: customers.php');
      }
    }
?>

<!DOCTYPE html>
<html lang="en" class="bg-primary h-100">
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
<!--Build our header and nav bar. The first body tag of each page was moved heere, along with any onload calls for that page.  -->
<body <?php if($url[0] == "editVessels.php"): ?> onload="checkVessels()" <?php elseif($url[0] == "editCustomer.php"): ?> onload="checkEdit()" <?php elseif($url[0] == "partsList.php" || $url[0] == "inspectionSheet.php" || $url[0] == "inspectionCert.php"): ?> onload="fillPartsChecklist()" <?php endif; ?>class="bg-primary h-100">
  <div class="container d-flex flex-column justify-content-between h-100">

    <nav class="navbar navbar-inverse container p-1">
      <div class="container-fluid justify-content-between bg-primary">
        <div class="navbar-header">
          <!-- <span class="navbar-brand"></span> -->
          <img src="images/Capstone Header Pic.png" height="50" width="100"/>
        </div>
        <div>
          <a href="home.php" class="text-white m-2<?php if(basename($_SERVER['PHP_SELF']) == 'home.php'): ?> active<?php endif; ?>">Home</a>
          <a href="logout.php" class="text-white m-2">Logout</a>
        </div>
      </div>
    </nav>
    <hr class="m-0 text-secondary opacity-100 border border-secondary border-1">