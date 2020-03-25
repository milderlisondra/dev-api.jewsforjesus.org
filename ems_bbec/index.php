<?php
error_reporting(E_ALL);
header('Content-Type: application/json'); 
spl_autoload_register('emsAutoloader');

function emsAutoloader($className){
    $path = 'models/';
    include $path.$className.'.php';
}

$response = array();

// Class instances
$ems = new EMSHub();

$received = json_decode(file_get_contents('php://input'), true);


extract($received);
if(isset($EmailAddress) && trim($EmailAddress) != "" ){
	if( $ems->save($received) != 0 ){
		echo json_response(200, 'Contact Saved');
	}else{
		echo json_response(500, 'Unable to Save Contact.');
	}
}else{
	echo json_response(400, 'Email address is required');
}

function json_response($code = 200, $message = null)
{
    // clear the old headers
    header_remove();
    // set the actual code
    http_response_code($code);
    // set the header to make sure cache is forced
    header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
    // treat this as json
    header('Content-Type: application/json');
    $status = array(
        200 => '200 OK',
        400 => '400 Bad Request',
        422 => 'Unprocessable Entity',
        500 => '500 Internal Server Error'
        );
    // ok, validation error, or failure
    header('Status: '.$status[$code]);
    // return the encoded json
    return json_encode(array(
        'status' => $code < 300, // success or not?
        'message' => $message
        ));
}


