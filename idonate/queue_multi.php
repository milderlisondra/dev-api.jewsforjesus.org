<?php
error_reporting(E_ERROR);

spl_autoload_register('iDonateAutoloader');

function iDonateAutoloader($className){
    $path = 'models/';
    include $path.$className.'.php';
}

// Class objects
$idonate = new DonateHub();
$infinity_obj = new Infinity();

$limit = 1;
if( isset($_GET['limit']) && is_numeric($_GET['limit']) ){
	$limit = $_GET['limit'];
}

print '<pre>';
$params = array('status'=>'Ready', 'limit'=>$limit);
$result = $idonate->get_header_transactions( $params );
extract($result[0]);


// get all transactions with matching Transaction Set ID
$params = array('field'=>'transaction_set_id','value'=>$transaction_set_id);
$transactions = $idonate->get_by_param($params);

// Map iDonate raw data to BBEC fields

if( $transactions != 0 ){
	

		foreach( $transactions as $data){

			extract($data);

			print '<br/>';
			if( $subtype == 'credit' ){
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

		/* Jay Jose - 11-25-2019 - set null or blank designation 
		to DEFAULT. Mostly, recurring transactions that were migrated 
		from magento to iDonate will have blank or null designation. */	
			if($fund_id == '' || $fund_id == null){
				$fund_id = 'DEFAULT';
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
			'STATE'=>$state,
			'POSTCODE'=>$zip,
			'COUNTRYID'=>$countryid,
			'COUNTRYCODE'=>$country_code,
			'TYPECODE'=>$typecode,
			'PAYMENTDATE'=>$paymentdate,
			'AMOUNT'=>$client_proceeds,
			'PAYMENTMETHODCODE'=>$paymentmethodcode,
			'CREDITTYPE'=>$card_type,
			'EXPIRESON'=>$expireson,
			'RECEIPTAMOUNT'=>$client_proceeds,
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
			'DONORPAIDFEE'=>$donor_paid_fee,
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

			$add_result = $infinity_obj->addIDonateTransaction( $donation_data);
			print $add_result;
			unset($donation_data);
			$count_processed++;
		}

	$params['field'] = 'bbec_status';
	$params['value'] = 'Done';
	$params['transaction_set_id'] = $transaction_set_id;
	$idonate->update_header_transaction($params);
	$message = "Transaction Set ID: " . $transaction_set_id;
	$idonate->log_action(array("action"=>"Successful Post of Split Gift","message"=>$message));
}
print '</pre>';


