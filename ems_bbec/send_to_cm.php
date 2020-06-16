<?php
error_reporting(E_ALL);
header('Content-Type: application/json'); 
spl_autoload_register('emsAutoloader');

function emsAutoloader($className){
    $path = 'models/';
    include $path.$className.'.php';
}

$list_id = 'f382c03b099b30557e36175e4256f5ff';
$cm_endpoint = 'https://api.createsend.com/api/v3.2/subscribers/' . $list_id . '.json';

$response = array();

// Class instances
$ems = new EMSHub();
$limit = 1;
if( isset($_GET['limit']) && is_numeric($_GET['limit']) ){
	$limit = $_GET['limit'];
}
$params = array('limit'=>$limit,'field'=>'DataSource','field_value'=>'BBEC');
$result = $ems->get_bbec_queue($params);
$subscriber_data = $result[0];

// Create payload for Campaign Monitor
foreach($subscriber_data as $key=>$value){
	if($key != 'Subscription'){
		$payload['CustomFields'][] = array("Key"=>$key,"Value"=>$value);
	}
}

if($result[0]['Subscription'][0] == ","){
	$subscription_string = substr($result[0]['Subscription'],1, strlen($result[0]['Subscription']));
}
// Create array of subscriptions
$subscription_array = explode(",",$subscriber_data['Subscription']);

foreach($subscription_array as $subscription_value){
	$payload['CustomFields'][] = array("Key"=>"Subscription","Value"=>$subscription_value);
}
// Add non-Custom Fields into payload
$payload['EmailAddress'] = $subscriber_data['EmailAddress'];
$payload['ConsentToTrack'] = 'Yes';
$payload['Name'] = $subscriber_data['FirstName'] . ' ' . $subscriber_data['LastName'];


// CURL Request to Campaign Monitor
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $cm_endpoint,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>json_encode($payload),
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "Authorization: Basic eGFNN1U2dkNYeUpHcm1EdkozQmVLeFZDaVFiRGZiei9CSzFLWmRkaC92UVNNeXRVUTcxMjY2bU53ZjFtOGhYVXlXdnljL09meVd3dHhoZ0cwVGkvaWZGSm5qcTVxQmQyeFVJOFNJV2h0RXBuTURudXc5WHpldGF2Qzl1K0VmL09hUmNNU3MxeXNWNWNqUUExRXVXdVFRPT06"
  ),
));

$response = curl_exec($curl);
curl_close($curl);

if($response == 1){
	$params = array("ID"=>$subscriber_data['ID'],"field"=>"EMSSaved","field_value"=>"Yes");
	$ems->update($params);
}
