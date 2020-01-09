<?php

class Infinity extends SoapClient {

	protected $donate_hub;
	protected $queued_ids = array();
	
	public function __construct() { 	
		$this->donate_hub = new DonateHub();
		
		if (empty($options) || !is_array($options)) {
			$options=array();
		}

		// Add the authentication information to our headers
		if (!array_key_exists('login', $options)) {
			$options["login"]="BLACKBAUDHOST\Unity17112";
		}
		if (!array_key_exists('password', $options)) {
			$options["password"]="Un1Ty17!"; 
		}

		if( $_SERVER['SERVER_NAME'] == 'localhost' ){
			$wsdl = 'https://bbecrig05bo3.blackbaudhosting.com/17112_c45ea4a9-6acc-417a-9b46-03139f3c9575/AppFxWebService.asmx?wsdl'; // STAGING
		}else{
			$wsdl = 'https://bbisecrigabo3.blackbaudhosting.com/17112_ed77bf07-ecb8-4c6f-b258-5fa8f1749b33/AppFxWebService.asmx?wsdl'; // PRODUCTION
		}
		return parent::__construct($wsdl, $options); 
	}

	public function __call($function, $args) {

		$clientAppInfo=array('REDatabaseToUse' => 'Jews for Jesus',
							 'SessionKey'      => $this->guid(),
							 'TimeOutSeconds'  => 120
							 );

		// Add the ClientAppInfoHeader to our request
		$params=array();

		foreach ($args as $key => $value) {
			if(is_array($value)) {
				foreach ($value as $key1 => $value1) {
					$params[$key1]=$value1;
				}
			} else {
				$params[$key]=$value;
			}
		}

		$params['ClientAppInfo'] = $clientAppInfo;
		$args=array($params);

		return $this->__soapCall($function, $args, $this->location, $this->headers);

	}
	
	private function guid(){
		if (function_exists('com_create_guid')){
			return com_create_guid();
		}else{
			mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			$uuid = chr(123)// "{"
					.substr($charid, 0, 8).$hyphen
					.substr($charid, 8, 4).$hyphen
					.substr($charid,12, 4).$hyphen
					.substr($charid,16, 4).$hyphen
					.substr($charid,20,12)
					.chr(125);// "}"
			return $uuid;
		}
	}
	
	/*
	* convertToPST
	* @param $utc_ts
	* Converts given UTC datetime to PST datetime
	* @return $new_time datetime object
	*/
	public function convertToPST($utc_ts){
		$tz_from = new DateTimeZone('UTC');
		$tz_to = new DateTimeZone('America/Los_Angeles');

		$orig_time = new DateTime($utc_ts, $tz_from);
		$new_time = $orig_time->setTimezone($tz_to);

		return $new_time;		
	}	
	
	public function addIDonateTransaction( $data ){
		
		extract($data);
		$client = new Infinity();

		try{
			$filterValues = array();

			$DATEADDED = $this->convertToPST($DATEADDED)->format("m/d/Y h:i:s");
			$PAYMENTDATE = $this->convertToPST($PAYMENTDATE)->format("Ymd"); // PAYMENTDATE requires a Fuzzy date
			$BATCHDATE = 'DH00000000';
			$REVENUECATEGORY = 'Mail Income';

			$firstname_count = $this->stopword_check($FIRSTNAME);
			$lastname_count = $this->stopword_check($KEYNAME);
			$total_stop_count = $firstname_count + $lastname_count;
			if( $total_stop_count > 0 ){
				$REVENUECATEGORY = 'Church Income';
			}
		
			$filterValues[] = $this->getStringFilter('DATEADDED', $DATEADDED );
			$filterValues[] = $this->getStringFilter('DATECHANGED', $DATEADDED );			
			$filterValues[] = $this->getStringFilter('LOOKUPID', $LOOKUPID);
			$filterValues[] = $this->getStringFilter('ALTERNATELOOKUPID', $ALTERNATELOOKUPID);
			$filterValues[] = $this->getStringFilter('ALTERNATELOOKUPIDTYPECODEID', $ALTERNATELOOKUPIDTYPECODEID);
			$filterValues[] = $this->getStringFilter('TITLE', $TITLE);
			$filterValues[] = $this->getStringFilter('FIRSTNAME',$FIRSTNAME);
			$filterValues[] = $this->getStringFilter('MIDDLENAME', $MIDDLENAME);
			$filterValues[] = $this->getStringFilter('KEYNAME', $KEYNAME);
			$filterValues[] = $this->getStringFilter('EMAILADDRESS', $EMAILADDRESS);
			$filterValues[] = $this->getStringFilter('PHONENUMBER', $PHONENUMBER);
			$filterValues[] = $this->getStringFilter('RELIGIONCODEID', $RELIGIONCODEID);
			$filterValues[] = $this->getStringFilter('BIRTHDATE', $BIRTHDATE);
			$filterValues[] = $this->getStringFilter('ADDRESSBLOCK', $ADDRESSBLOCK);
			$filterValues[] = $this->getStringFilter('CITY', $CITY);
			$filterValues[] = $this->getStringFilter('STATEID', $STATEID);
			$filterValues[] = $this->getStringFilter('STATE', $STATE);
			$filterValues[] = $this->getStringFilter('POSTCODE', $POSTCODE);
			$filterValues[] = $this->getStringFilter('COUNTRYID', $COUNTRYID);
			$filterValues[] = $this->getStringFilter('COUNTRYCODE', $COUNTRYCODE);
			$filterValues[] = $this->getStringFilter('LOCALPRECINCTCODEID', '');
			$filterValues[] = $this->getStringFilter('LANGUAGECODEID', '');
			$filterValues[] = $this->getBooleanFilter('ISJEWISHGENTILECOUPLE', $ISJEWISHGENTILECOUPLE);
			$filterValues[] = $this->getStringFilter('MISSIONARYSTAFFID', '');
			$filterValues[] = $this->getBooleanFilter('EMAILOPTIN', $EMAILOPTIN);
			$filterValues[] = $this->getStringFilter('CRITICALNOTES', '');
			$filterValues[] = $this->getStringFilter('ALTERNATELOOKUPIDS', '');
			$filterValues[] = $this->getStringFilter('ISALTERNATELOOKUPIDUPDATE', 'false');
			$filterValues[] = $this->getStringFilter('FACEBOOKURL', '');
			$filterValues[] = $this->getStringFilter('PRIMARYSITEID', '');
			$filterValues[] = $this->getStringFilter('NONPRIMARYLANGUAGEIDS', '');
			$filterValues[] = $this->getDecimalFilter('TYPECODE', '0');
			$filterValues[] = $this->getStringFilter('PAYMENTDATE', $PAYMENTDATE );
			$filterValues[] = $this->getFloatFilter('AMOUNT', $AMOUNT);
			$filterValues[] = $this->getDecimalFilter('PAYMENTMETHODCODE', '10');
			$filterValues[] = $this->getStringFilter('CREDITTYPE', '83EF7B97-4D6A-448A-9838-8C5DD609FE77');
			$filterValues[] = $this->getStringFilter('EXPIRESON', $EXPIRESON );
			$filterValues[] = $this->getFloatFilter('RECEIPTAMOUNT', $RECEIPTAMOUNT);
			$filterValues[] = $this->getDecimalFilter('SEQUENCE', '0');
			$filterValues[] = $this->getStringFilter('FREQUENCY', $FREQUENCY);
			$filterValues[] = $this->getStringFilter('SUBTYPE', $SUBTYPE);
			$filterValues[] = $this->getStringFilter('PAYMENTGATEWAYID', $PAYMENTGATEWAYID);
			$filterValues[] = $this->getStringFilter('DESIGNATIONFUNDID', $DESIGNATIONFUNDID);
			$filterValues[] = $this->getStringFilter('CUSTOMNOTE1', $CUSTOMNOTE1);
			$filterValues[] = $this->getStringFilter('PAYLOADID', $PAYLOADID);
			$filterValues[] = $this->getStringFilter('REFERENCECODE', $REFERENCECODE);
			$filterValues[] = $this->getStringFilter('BATCHDATE', $BATCHDATE);
			$filterValues[] = $this->getStringFilter('REVENUECATEGORY', $REVENUECATEGORY);
			$filterValues[] = $this->getDecimalFilter('RECURRINGCOUNT', $RECURRINGCOUNT);
			$filterValues[] = $this->getFloatFilter('DONORPAIDFEE', $DONORPAIDFEE);
			
			$dataformitem             = new stdClass;
			$dataformitem->Values     = new stdClass;
			$dataformitem->Values->fv = $filterValues;
			
			$params=array(
				'FormID' => '2318875c-b0fc-4e23-8f38-2020b3dda761',
				'FileUploadKey' => $this->guid(),
				'DataFormItem' => $dataformitem
			);


			$result = $client->DataFormSave($params);
/* 			print '<pre>';
			print_r($result);
			print '</pre>'; */
			$message = "BBEC RETURNED GUID: " . $result->ID . "\r\n";
			$message .= "QUEUEID " . $QUEUEID . "\r\n";
			$message .= "POSTED DATA: " . "\r\n";
			$message .= print_r($params,true);
			$this->donate_hub->log_action(array("action"=>"Successful Post to BBEC","message"=>$message));
			$this->donate_hub->update(array("table_id"=>$QUEUEID,"status"=>"Done"));

			return $result->ID;
			
		}catch (Exception $f){
$this->donate_hub->update(array("table_id"=>$QUEUEID,"status"=>"Error"));
			$message = print_r($f, true) . "\r\n";
			$message .= "QUEUEID " . $QUEUEID;
			$log_file_name = 'log/BBEC-error-' . $QUEUEID . '-' . time() . '.log'; 
			error_log($message, 3, $log_file_name);				

			return 0;
		}
	}
	
	private function getSoapString($stringVar) {
		try{
			return new SoapVar($stringVar, XSD_STRING, "string", "http://www.w3.org/2001/XMLSchema");
		}catch (Exception $e){
			print_r($e);
		}
	}

	private function getSoapBoolean($booleanVar) {
		return new SoapVar($booleanVar, XSD_BOOLEAN, "boolean", "http://www.w3.org/2001/XMLSchema");
	}

	private function getSoapDecimal($decimalVar) {
		return new SoapVar($decimalVar, XSD_DECIMAL, "decimal", "http://www.w3.org/2001/XMLSchema");
	}

	private function getSoapFloat($floatVar) {
		return new SoapVar($floatVar, XSD_FLOAT, "float", "http://www.w3.org/2001/XMLSchema");
	}
	
	private function getStringFilter($fieldID, $fieldValue, $fieldValueTrans='') {
		$fv = new stdClass;
		$fv->Value = $this->getSoapString($fieldValue);
		$fv->ID=$fieldID;

		if (strlen($fieldValueTrans) > 0) {
			$fv->ValueTranslation=$fieldValueTrans;
			}

		return $fv;
	}
	

	private function getBooleanFilter($fieldID, $fieldValue) {
		$fv = new stdClass;
		if ($fieldValue == 'on') {
			$fv->Value = $this->getSoapBoolean(true);
			}
		else {
			$fv->Value = $this->getSoapBoolean(false);
			}

		$fv->ID=$fieldID;

		return $fv;
	}

	private function getDecimalFilter($fieldID, $fieldValue) {
		$fv = new stdClass;
		$fv->Value = $this->getSoapDecimal($fieldValue);
		$fv->ID=$fieldID;

		return $fv;
	}

	private function getFloatFilter($fieldID, $fieldValue) {
		$fv = new stdClass;
		$fv->Value = $this->getSoapFloat($fieldValue);
		$fv->ID=$fieldID;

		return $fv;
	}

	// Check to see if certain words exist within given string, $string_to_check
	private function stopword_check($string_to_check){
		$church_income_stopwords = array('Church','Fellowship','Ministries','Iglesia','Assembly','Outreach','Center','Mission','Cathedral');
		
		$found_count = 0;
		
		foreach($church_income_stopwords as $stop_word){
			if (strpos(strtolower($string_to_check), strtolower($stop_word)) !== false) {
				$found_count++;
			}			
		}
		return $found_count;
	}	
	
}

