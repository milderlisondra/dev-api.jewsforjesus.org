<?php
// main file
error_reporting(E_ALL);

spl_autoload_register('iDonateAutoloader');

function iDonateAutoloader($className){
    $path = 'models/';
    include $path.$className.'.php';
}

// Class instances
$hub = new DonateHub();

$received = json_decode(file_get_contents('php://input'), true);

// check to see if iDonate id is already in the system
$params['field'] = 'id';
$params['value'] = $received['id'];
$result = $hub->get_by_param($params);

if( $result == 0 ){
	extract($received['payload']);
	if( $received['type'] == 'transaction.completed' ){
		
		$transaction['donation_type'] = 'single';
		if (strpos($description, 'Cash Multi-Donation') !== false) {
			$transaction['donation_type'] = 'multi';
		}
		
		if($recurring_count >= 1 ){
			$reference_code = 'STNETREC';
		}else{
			if( isset($reference_code) ){
				$reference_code = trim($reference_code);
			}else{
				if( $embed['id'] == '8bd8823c-8a50-4f37-839d-ccb0938f3b84' || $embed['name'] == 'Offline' ){
					$reference_code = 'STPHONE';
				}else{
					$reference_code = 'STNETDON';
				}
			}
		}
		
		// Setup main information
		$transaction['created'] = $received['created'];
		$transaction['id'] = $received['id'];
		$transaction['type'] = $received['type'];

		$transaction['additional_info'] = $additional_info;
		$transaction['advocacy_program_id'] = $advocacy_program_id;
		$transaction['advocacy_program_name'] = $advocacy_program_name;
		$transaction['advocacy_team_id'] = $advocacy_team_id;
		$transaction['advocacy_team_name'] = $advocacy_team_name;
		$transaction['advocate'] = $advocate;
		$transaction['advocate_id'] = $advocate_id;
		$transaction['advocate_name'] = $advocate_name;
		$transaction['campaign_id'] = $campaign_id;
		$transaction['campaign_title'] =  $campaign_title;
		$transaction['card_type'] =  $card_type;
		$transaction['check_number'] =  $check_number;
		$transaction['client_proceeds'] =  $client_proceeds;
		$transaction['corporate_matching_record'] = $corporate_matching_record;
		$transaction['custom_note_1'] = $custom_note_1;
		$transaction['custom_note_2'] = $custom_note_2;
		$transaction['custom_note_3'] = $custom_note_3;
		$transaction['custom_note_4'] = $custom_note_4;
		$transaction['custom_note_5'] = $custom_note_5;
		$transaction['description'] = $description;

		// Setup designation
		$transaction['fund_id'] = $designation['fund_id'];
		$transaction['designation_id'] = $designation['id'];
		$transaction['designation_title'] = $designation['title'];
		$transaction['designation_note'] = $designation_note;
			
			
		// Setup contact information
		$transaction['firstname'] = $contact['firstname'];
		$transaction['lastname'] = $contact['lastname'];
		$transaction['email'] = $contact['email'];
		$transaction['middlename'] = $contact['middlename'];
		$transaction['phone'] = $contact['phone'];
		$transaction['timezone'] = $contact['timezone'];
		$transaction['title'] = $contact['title'];
		$transaction['updated'] = $contact['updated'];

		$transaction['city'] = $contact['address']['city'];
		$transaction['country'] = $contact['address']['country'];
		$transaction['country_code'] = $contact['address']['country_code'];
		$transaction['country_name'] = $contact['address']['country_name'];
		$transaction['state'] = $contact['address']['state'];
		$transaction['street'] = $contact['address']['street'];
		$transaction['street2'] = $contact['address']['street2'];
		$transaction['zip'] = $contact['address']['zip'];
		$transaction['donor_id'] = $donor_id;

		$transaction['donor_paid_fee'] = $donor_paid_fee;
		$transaction['email_opt_in'] = $email_opt_in;
		$transaction['embed_id'] = $embed['id'];
		$transaction['embed_name'] = $embed['name'];
		$transaction['expiration_month'] = $expiration_month;
		$transaction['expiration_year'] = $expiration_year;
		$transaction['external_tracking_id'] = $external_tracking_id;

		$transaction['final_date'] = $final_date;
		$transaction['frequency'] = $frequency;
		$transaction['gift'] = $gift;
		$transaction['gift_extra'] = $gift_extra;
		$transaction['hide_name'] = $hide_name;
		$transaction['last_four_digits'] = $last_four_digits;
		$transaction['net_proceeds'] = $net_proceeds;
		$transaction['organization_id'] = $organization_id;
		$transaction['payment_gateway_id'] = $payment_gateway_id;
		$transaction['payment_gateway_name'] = $payment_gateway_name;
		$transaction['payment_transaction_id'] = $payment_transaction_id;
		$transaction['recurring_count'] = $recurring_count;
		$transaction['reference_code'] = $reference_code;
		$transaction['sale_price'] = $sale_price;
		$transaction['schedule_id'] = $schedule_id;
		$transaction['sms_keyword'] = $sms_keyword;
		$transaction['status'] = $status;
		$transaction['subtype'] = $subtype;
		$transaction['transaction_set_id'] = $transaction_set_id;
		$transaction['tribute'] = $tribute;
		$transaction['type'] = $type;

		// UTM parameters
		$transaction['campaign'] = $utm['campaign'];
		$transaction['content'] = $utm['content'];
		$transaction['medium'] = $utm['medium'];
		$transaction['source'] = $utm['source'];
		$transaction['vendor_id'] = $vendor_id;

		// Create new DonateHub object
		$result = $hub->save($transaction);
		if( $result != 0){
			print $result;
			// Create a log file which contains the raw data
			$received_data_toarray = print_r(json_decode(file_get_contents('php://input'), true),true);
			$log_file_name = 'log/mc-import-' . $transaction['id'] . '.log'; 
			error_log($received_data_toarray, 3, $log_file_name);			
		}else{
			$received_data_toarray = print_r(json_decode(file_get_contents('php://input'), true),true);
			$log_file_name = 'log/mc-import-' . $transaction['id'] . '.log'; 
			$message = 'Unable to save idonate raw data to db\r\n';
			$message .= $received_data_toarray; 
			error_log($received_data_toarray, 3, $log_file_name);			
		}


	}

}else{
	$hub->log_action(array("action"=>"Duplicate Transaction from iDonate","message"=>json_encode($received)));
}
