<?php
error_reporting(E_ERROR);
print '<pre>';
spl_autoload_register('iDonateAutoloader');

function iDonateAutoloader($className){
    $path = 'models/';
    include $path.$className.'.php';
}

// Create objects
$hub = new DonateHub();

$limit = 1;
if( isset($_GET['limit']) && is_numeric($_GET['limit']) ){
	$limit = $_GET['limit'];
}

$params = array('status'=>'Pending', 'limit'=>$limit);
$result = $hub->get_header_transactions( $params );
if( $result != 0 ){
	extract($result[0]);
	print 'Updating Transactions with the following Transaction Set ID: ' . $transaction_set_id;
	print '<br/>';
	$header_data['transaction_set_id'] = $transaction_set_id;
	$header_data['payment_gateway_id'] = $payment_gateway_id;
	$header_data['frequency'] = $frequency;
	$header_data['reference_code'] = $reference_code;
	$header_data['recurring_count'] = $recurring_count;
	$header_data['card_type'] = $card_type;
	$header_data['custom_note_1'] = $custom_note_1;	
	$header_data['expiration_month'] = $expiration_month;	
	$header_data['expiration_year'] = $expiration_year;	
	if( $subtype == 'credit' && isset($subtype) ){
		if( isset($expiration_month) && isset($expiration_year) ){
			$month = (int) $expiration_month;
			$new_month = $month; 
			if($month < 10 ){
				$new_month = (string) $month;
				$new_month = "0" . $new_month;
			}
			$expiration_month = $new_month;
			$expireson_date = strtotime("$expiration_year-$expiration_month-00");
			$expireson = $expiration_year."".$expiration_month."00";			
		}else{
			$expireson = "00000000";
		}
	}else{
		$expireson = "00000000";
	}
	$header_data['expireson'] = $expireson;			
	// get all records from Transactions table
	$update_result = $hub->update_multi($header_data);
	if( $update_result === true ){
		// Set Header Transaction to status Ready
		$params = array("field"=>"bbec_status","value"=>"Ready","transaction_set_id"=>$transaction_set_id);
		$hub->update_header_transaction( $params );
	}
}
print '<pre/>';

die();