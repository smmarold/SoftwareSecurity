<?php
//Basic functions for checking post and gets. Looks cleaner on calling page. Page can also be used for general purpose functions that may be written in the future. 
function isPostRequest() {
    return ( filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST' );
}

function isGetRequest() {
    return ( filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'GET' && !empty($_GET) );
}

?>