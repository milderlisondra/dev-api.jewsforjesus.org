<?php
error_reporting(E_ALL);
spl_autoload_register('emsAutoloader');

function emsAutoloader($className){
    $path = 'models/';
    include $path.$className.'.php';
}

// Class instances
$ems = new EMSHub();
$infinity_obj = new Infinity();

$received = json_decode(file_get_contents('php://input'), true);
extract($received);

	$birthdate = '00000000';
	$emailoptin = 'true';
	$isjewishgentilecouple = 'false';
	$addressblock = '';
	$title = null;
	$localprecinctcodeid = '';
	$languagecodeid = '';
	$lookupid = '';
	$contact_code = '6F98A173-259A-491E-A3DD-40957FACB7FD';
	$city = '';
	$state = '';
	$zip = '';
	$country_code = 'US';
	$donor_id = '';
	$phone = '';

$contact = array(
	'FIRSTNAME'=>$firstname,
	'MIDDLENAME'=>$middlename,
	'KEYNAME'=>$lastname,
	'EMAILADDRESS'=>$emailaddress,
	'ADDRESSBLOCK'=>$addressblock,
	'CITY'=>$city,
	'STATE'=>$state,
	'POSTCODE'=>$zip,
	'COUNTRYCODE'=>$country_code,
	'LOOKUPID'=>$lookupid,
	'ALTERNATELOOKUPID'=>$donor_id,
	'BIRTHDATE'=>$birthdate,
	'EMAILOPTIN'=>$emailoptin,
	'RELIGIONCODEID' => $contact_code,
	'PHONENUMBER' => $phone,
	'ALTERNATELOOKUPIDTYPECODEID' => '',
	'MISSIONARYSTAFFID' => '',
	'CRITICALNOTES' => '',
	'ALTERNATELOOKUPIDS' => '',
	'ISALTERNATELOOKUPIDUPDATE' => 'false'
	);


	$add_result = $infinity_obj->sendContact( $contact );
	if($add_result != 0){
		unset($donation_data);
		$count_processed++;		
	}



