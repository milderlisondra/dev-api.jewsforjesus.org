<?php
error_reporting(E_ERROR);

spl_autoload_register('iDonateAutoloader');

function iDonateAutoloader($className){
    $path = '../models/';
    include $path.$className.'.php';
}
$idonate = new DonateHub();
$infinity = new Infinity();

if( isset($_POST['action']) ){
	if( $_POST['action'] == 'get_home_list'){
		
		$transactions = $idonate->get(array('status'=>'All'));
		
		foreach($transactions as $transaction){
			extract($transaction);
			
			$donation_amount = "$".number_format($client_proceeds,2,".",",");
			
			if( $subtype == 'echeck'){
				$payment_type = 'ACH';
				$card_type = 'N/A';
			}else{
				$payment_type = 'CC';
			}				
			
			//$transaction_date = $infinity->convertToPST($created)->format("m/d/Y");
			$transaction_date = date("n/d/Y",strtotime($created_datetime));
			$data['Date Added'] = $transaction_date;
			$data['Donation Amount'] = $donation_amount;
			$data['Client Proceeds'] = $donation_amount;
			$data['First Name'] = $firstname;
			$data['Last Name'] = $lastname;
			$data['Email'] = $email;
			$data['Payment Type'] = $payment_type;
			$data['Card Type'] = $card_type;
			$data['Description'] = $description;
			$data['Transaction Set ID'] = $transaction_set_id;
			$data['BBEC Status'] = $bbec_status;
			$all_data[] = $data;
		}
		

		//print json_encode($all_data);
		print json_encode(array("data"=>$all_data));
	}
}
