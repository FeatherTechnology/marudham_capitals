<?php
@session_start();
include('config-file.php');
// include("iedit-config.php");
include("adminclass.php");

$userObj = new admin();
$idupd = '';
$user_id = $_SESSION['userid']??'';

$getuserdetails  = $userObj->getuser($mysqli, $user_id);
