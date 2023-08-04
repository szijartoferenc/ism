<?php

//start the SESSION
session_start();

session_unset();

session_destroy();

$_SESSION['response'] = $response;
header('location: ../' .$_SESSION['redirect_to'])

?>