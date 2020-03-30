<?php 
include_once '../DRBController.php';
session_start();
$block = $_SESSION['block'];
$pagination = new DRBFunc();

$pagination->ledgerblock($block);

?>