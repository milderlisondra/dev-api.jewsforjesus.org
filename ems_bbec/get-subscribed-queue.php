<?php

// Followed instructions from the following MSFT documentation
// https://docs.microsoft.com/en-us/azure/service-bus-messaging/service-bus-php-how-to-use-queues

error_reporting(E_ERROR);
spl_autoload_register('emsAutoloader');

function emsAutoloader($className){
    $path = 'models/';
    include $path.$className.'.php';
}

// Class instances
$ems = new EMSHub();
$infinity_obj = new Infinity();

require_once 'vendor/autoload.php';

use WindowsAzure\Common\ServicesBuilder;
use WindowsAzure\Common\ServiceException;
use WindowsAzure\ServiceBus\Models\ReceiveMessageOptions;

$connectionString = "Endpoint=https://emsbbec.servicebus.windows.net/;SharedAccessKeyName=RootManageSharedAccessKey;SharedAccessKey=FSOTvELxJcDpiWuejb7SUzKe2uuV3kDX4OMkzmsTF1A=";	

$serviceBusRestProxy = ServicesBuilder::getInstance()->createServiceBusService($connectionString);

try    {

    // Set the receive mode to PeekLock (default is ReceiveAndDelete).
    $options = new ReceiveMessageOptions();
    $options->setPeekLock();

    // Receive message.
    $message = $serviceBusRestProxy->receiveQueueMessage("subscribes", $options);
 	echo "Body: ".$message->getBody()."<br />";
    //echo "MessageID: ".$message->getMessageId()."<br />";

    $contact_data = json_decode($message->getBody(),true);
    extract($contact_data);
    print_r($Events);
    extract($Events[0]);
    print 'Email Address: '. $EmailAddress . PHP_EOL;

    $emailaddress = $EmailAddress;

    foreach($CustomFields as $key=>$value){
    	if(is_array($value)){
    		extract($value);
    		print $Key  . PHP_EOL;
    		switch($Key){
    			case "First Name":
    				$firstname = $Value;
    				break;
    			case "Last Name":
    				$lastname = $Value;
    				break;
    		}
    	}
    	
    }

    print 'FIRST Name: ' . $firstname . PHP_EOL;

//$decoded_json = json_decode($json_message,true);
//print_r($decoded_json);
    // Send the data to BBEC
    // If the send is successful, delete the message from the queue


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
	$altlookupid = get_uuid();

	print PHP_EOL;
	print 'ALT LOOKUPID: ' . $altlookupid . PHP_EOL;
	$contact = array(
		'FIRSTNAME'=>$firstname,
		'KEYNAME'=>$lastname,
		'EMAILADDRESS'=>$emailaddress,
		'ADDRESSBLOCK'=>$addressblock,
		'CITY'=>$city,
		'STATE'=>$state,
		'POSTCODE'=>$zip,
		'COUNTRYCODE'=>$country_code,
		'LOOKUPID'=>$lookupid,
		'ALTERNATELOOKUPID'=>$altlookupid,
		'BIRTHDATE'=>$birthdate,
		'EMAILOPTIN'=>$emailoptin,
		'RELIGIONCODEID' => $contact_code,
		'PHONENUMBER' => $phone,
		'MISSIONARYSTAFFID' => '',
		'CRITICALNOTES' => '',
		'ALTERNATELOOKUPIDS' => '',
		'ISALTERNATELOOKUPIDUPDATE' => 'false'
		);


	$add_result = $infinity_obj->sendContact( $contact );
	print PHP_EOL;
	print_r($add_result);
	if($add_result != 0){

		$count_processed++;		

	    // Delete message. Not necessary if peek lock is not set.
	    //echo "Message deleted.<br />";
	   //$serviceBusRestProxy->deleteMessage($message);

	}

}
catch(ServiceException $e){
    // Handle exception based on error codes and messages.
    // Error codes and messages are here:
    // https://docs.microsoft.com/rest/api/storageservices/Common-REST-API-Error-Codes
    $code = $e->getCode();
    $error_message = $e->getMessage();
    echo $code.": ".$error_message."<br />";
}


function get_uuid(){
	mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
        return $uuid;
}


