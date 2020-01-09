<?php
error_reporting(E_ERROR);

spl_autoload_register('iDonateAutoloader');

function iDonateAutoloader($className){
    $path = 'models/';
    include $path.$className.'.php';
}

// Create new DonateHub object
$donation_hub = new DonateHub();
$num_days_back = 10;
if( isset($_POST['num']) && is_numeric($_POST['num']) && $_POST['num'] >= 10 ){
	$num_days_back = $_POST['num'];
	$result = $donation_hub->expunge($num_days_back);
	print $result;	
}