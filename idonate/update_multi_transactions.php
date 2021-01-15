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
$infinity_obj = new Infinity();

$limit = 1;
if( isset($_GET['limit']) && is_numeric($_GET['limit']) ){
	$limit = $_GET['limit'];
}

$params = array('status'=>'Pending', 'limit'=>$limit);
$result = $hub->get_header_transactions( $params );
if( $result != 0 ){
	extract($result[0]);
	print 'Updating Header record(s) with the following Transaction Set ID: ' . $transaction_set_id;
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

			$dateadded = date("Ymd", strtotime($created)); 
			$datechanged = date("Ymd", strtotime($updated)); 
			$paymentdate = $created;	
			$date = DateTime::createFromFormat('j-M-Y', '15-Feb-2009');
			$paymentmethodcode = '10';
			$revenuecategory = 'Mail Income';
			$birthdate = '00000000';
			$emailoptin = 'true';
			$isjewishgentilecouple = 'false';
			$typecode = 0;
			$addressblock = $street . ' ' . $street2;
			$stateid = null;
			$countryid = 'C6923EF7-606B-400A-B415-BEE6704E9625';
			$sequence = '0';
			$title = null;
			$localprecinctcodeid = '';
			$languagecodeid = '';
			$lookupid = $custom_note_5;
			
			
			if($donor_paid_fee == ''){
				$donor_paid_fee = '0.00';
			}

			if($recurring_count == ''){
				$recurring_count = '0';
			}


		$donation_data = array(
				'DATEADDED'=>$created,
				'DATECHANGED'=>$created,
				'FIRSTNAME'=>$firstname,
				'MIDDLENAME'=>$middlename,
				'KEYNAME'=>$lastname,
				'EMAILADDRESS'=>$email,
				'ADDRESSBLOCK'=>$addressblock,
				'CITY'=>$city,
				'STATEID'=>$stateid,
				'STATE'=>'New York',
				'POSTCODE'=>$zip,
				'COUNTRYID'=>$countryid,
				'COUNTRYCODE'=>$country_code,
				'TYPECODE'=>$typecode,
				'PAYMENTDATE'=>$created,
				'AMOUNT'=>$client_proceeds,
				'PAYMENTMETHODCODE'=>$paymentmethodcode,
				'CREDITTYPE'=>$credittype,
				'EXPIRESON'=>$expireson,
				'RECEIPTAMOUNT'=>$net_proceeds,
				'SEQUENCE'=>$sequence,
				'FREQUENCY'=>$frequency,
				'SUBTYPE'=>$subtype,
				'PAYMENTGATEWAYID'=>$payment_gateway_id,
				'DESIGNATIONFUNDID'=>$fund_id,
				'CUSTOMNOTE1'=>$custom_note_1,
				'PAYLOADID'=>$id,
				'REFERENCECODE'=>$reference_code,
				'BATCHDATE'=>'DH00000000',
				'REVENUECATEGORY'=>$revenuecategory,
				'RECURRINGCOUNT'=>$recurring_count,
				'DONORPAIDFEE'=>$client_proceeds,
				'LOOKUPID'=>$lookupid,
				'ALTERNATELOOKUPID'=>$donor_id,
				'TITLE'=>$title,
				'CREDITTYPE'=>$card_type,
				'BIRTHDATE'=>$birthdate,
				'EMAILOPTIN'=>$emailoptin,
				'ISJEWISHGENTILECOUPLE'=>$isjewishgentilecouple,
				'RELIGIONCODEID' => '6F98A173-259A-491E-A3DD-40957FACB7FD',
				'PHONENUMBER' => $phone,
				'LOCALPRECINCTCODEID' => $localprecinctcodeid,
				'LANGUAGECODEID' => $languagecodeid,
				'ALTERNATELOOKUPIDTYPECODEID' => '',
				'MISSIONARYSTAFFID' => '',
				'CRITICALNOTES' => '',
				'ALTERNATELOOKUPIDS' => '',
				'ISALTERNATELOOKUPIDUPDATE' => 'false',
				'FACEBOOKURL' => '',
				'PRIMARYSITEID' => '',
				'NONPRIMARYLANGUAGEIDS' => '',
				'TYPECODE' => '',
				'QUEUEID'=>$table_id
				);

		$bbec_post_result = $infinity_obj->addIDonateTransaction($donation_data);
		print_r($bbec_post_result);
	}
}
print '<pre/>';


function save_header_data( $param ){
	
	global $header_data;
	
	$header_data = $param;

}

function retrieve_header_data( $key ){
	global $header_data;
	
	print $key . ' : ' . $header_data[$key];
	print '<br/>';	
	return $header_data[$key];
}