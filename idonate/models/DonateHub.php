<?php
class DonateHub extends Database{
	
	protected $transactions = "transactions";
	protected $transactions_header = "transactions_header";
	protected $infinity;
	
	public function __construct(){
		$this->conn = parent::__construct(); // get db connection from Database model
		$this->ts = date("Y-m-d H:i:s",time()); // set current timestamp
	}

	
	/**
	* get
	* Retrieve transactions
	* @param array/mixed @params
	*
	*/
	public function get( $params ){
		$status = 'Pending';
		$order = 'DESC';
		$limit = 5;
		$donation_type = 'single';

		if(isset($params['limit'])){
			$limit = $params['limit'];
		}
		if(isset($params['status'])){
			$status = $params['status'];
		}	
		if(isset($params['order'])){
			$order = $params['order'];
		}		
		if(isset($params['donation_type'])){
			$donation_type = $params['donation_type'];
		}	
		
		$return_data = array();
		if($status == 'All'){
			$query = "SELECT * FROM `".$this->transactions."` WHERE `bbec_status` IN ('Pending','Done') ORDER BY `created`" . $order;
		}else{
			$query = "SELECT * FROM `".$this->transactions."` WHERE `bbec_status`='".$status."' AND `donation_type`='".$donation_type."' ORDER BY `created`" . $order . ' LIMIT ' . $limit;
		}

		$stmt = $this->conn->prepare($query);	
		$stmt->execute();

			if($stmt->rowCount() > 0) {
				while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
				   $return_data[] = $result;
				}
				return $return_data;
			}else{
				return 0;
			}
		
	}

	/**
	* get_multi
	* Retrieve transactions
	* @param array/mixed @params
	*
	*/
	public function get_multi( $params ){
		$status = 'Pending';
		$order = 'DESC';
		$limit = 5;
		$donation_type = 'multi';

		if(isset($params['limit'])){
			$limit = $params['limit'];
		}
		if(isset($params['status'])){
			$status = $params['status'];
		}	
		if(isset($params['order'])){
			$order = $params['order'];
		}
		if(isset($params['donation_type'])){
			$donation_type = $params['donation_type'];
		}		

		$return_data = array();
		if($status == 'All'){
			$query = "SELECT * FROM `".$this->transactions."` ORDER BY `created`" . $order;
		}else{
			$query = "SELECT DISTINCT(transaction_set_id) FROM `".$this->transactions."` WHERE `donation_type`='multi' AND `bbec_status`='".$status."' ORDER BY `created`" . $order . ' LIMIT ' . $limit;
		} 

		$stmt = $this->conn->prepare($query);	
		$stmt->execute();

			if($stmt->rowCount() > 0) {
				while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
				   $return_data[] = $result;
				}
				return $return_data;
			}else{
				return 0;
			}
		
	}

	//
	/**
	* get_by_param
	* Retrieve record using given field name and corresponding value
	* @params array
	*
	*/
	public function get_by_param( $params ){
		$field = $params['field'];
		$value = $params['value'];
		$return_data = "";
		$query = "SELECT * FROM `".$this->transactions."` WHERE `".$field."`='".$value."'";
		
		$stmt = $this->conn->prepare($query);	
		$stmt->execute();
			if($stmt->rowCount() == 1) {
				$return_data = $stmt->fetch(PDO::FETCH_ASSOC);
				return $return_data;
			}elseif( $stmt->rowCount() > 1 ){
				$return_data = array();
				while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
				   $return_data[] = $result;
				}
				return $return_data;
			}else{
				return 0;
			}
/* 			if($stmt->rowCount() > 0) {
				while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
				   $return_data[] = $result;
				}
				return $return_data;
			}else{
				return 0;
			} */
		
	}	
	
	
	/**
	* Save transaction to db
	* @param array $data
	* @return mixed $result ( id on success; 0 on failure )
	*/
	public function save($data){
		$this->infinity = new Infinity();
		
		extract($data);
		
		$created_datetime = $this->infinity->convertToPST($created)->format("Y-m-d h:i:s");
		
		if (strpos($description, 'Cash Multi-Donation [0') !== false || $client_proceeds == 0 ) {
			$table = $this->transactions_header;
		}else{
			$table = $this->transactions;
		}
		$stmt = $this->conn->prepare("INSERT INTO `".$table."` (created,
		id,
		type,
		additional_info,
		advocacy_program_id,
		advocacy_program_name,
		advocacy_team_id,
		advocacy_team_name,
		advocate,
		advocate_id,
		advocate_name,
		campaign_id,
		campaign_title,
		card_type,
		check_number,
		client_proceeds,
		corporate_matching_record,
		custom_note_1,
		custom_note_2,
		custom_note_3,
		custom_note_4,
		custom_note_5,
		description,
		fund_id,
		designation_id,
		designation_title,
		designation_note,
		firstname,
		lastname,
		email,
		middlename,
		phone,
		timezone,
		title,
		updated,
		city,
		country,
		country_code,
		country_name,
		state,
		street,
		street2,
		zip,
		donor_id,
		donor_paid_fee,
		email_opt_in,
		expiration_month,
		expiration_year,
		external_tracking_id,
		final_date,
		frequency,
		gift,
		gift_extra,
		hide_name,
		last_four_digits,
		net_proceeds,
		organization_id,
		payment_gateway_id,
		payment_gateway_name,
		payment_transaction_id,
		recurring_count,
		reference_code,
		sale_price,
		schedule_id,
		sms_keyword,
		status,
		subtype,
		transaction_set_id,
		tribute,
		campaign,
		content,
		medium,
		source,
		vendor_id,embed_id,embed_name, donation_type,created_datetime) 
		VALUES (:created,
		:id,
		:type,
		:additional_info,
		:advocacy_program_id,
		:advocacy_program_name,
		:advocacy_team_id,
		:advocacy_team_name,
		:advocate,
		:advocate_id,
		:advocate_name,
		:campaign_id,
		:campaign_title,
		:card_type,
		:check_number,
		:client_proceeds,
		:corporate_matching_record,
		:custom_note_1,
		:custom_note_2,
		:custom_note_3,
		:custom_note_4,
		:custom_note_5,
		:description,
		:fund_id,
		:designation_id,
		:designation_title,
		:designation_note,
		:firstname,
		:lastname,
		:email,
		:middlename,
		:phone,
		:timezone,
		:title,
		:updated,
		:city,
		:country,
		:country_code,
		:country_name,
		:state,
		:street,
		:street2,
		:zip,
		:donor_id,
		:donor_paid_fee,
		:email_opt_in,
		:expiration_month,
		:expiration_year,
		:external_tracking_id,
		:final_date,
		:frequency,
		:gift,
		:gift_extra,
		:hide_name,
		:last_four_digits,
		:net_proceeds,
		:organization_id,
		:payment_gateway_id,
		:payment_gateway_name,
		:payment_transaction_id,
		:recurring_count,
		:reference_code,
		:sale_price,
		:schedule_id,
		:sms_keyword,
		:status,
		:subtype,
		:transaction_set_id,
		:tribute,
		:campaign,
		:content,
		:medium,
		:source,
		:vendor_id,
		:embed_id,
		:embed_name,
		:donation_type,
		:created_datetime)");
		
		// Bind parameters
		$stmt->bindParam(':created',$created, PDO::PARAM_STR);
		$stmt->bindParam(':id',$id, PDO::PARAM_STR);
		$stmt->bindParam(':type',$type, PDO::PARAM_STR);
		$stmt->bindParam(':additional_info',$additional_info, PDO::PARAM_STR);
		$stmt->bindParam(':advocacy_program_id',$advocacy_program_id, PDO::PARAM_STR);
		$stmt->bindParam(':advocacy_program_name',$advocacy_program_name, PDO::PARAM_STR);
		$stmt->bindParam(':advocacy_team_id',$advocacy_team_id, PDO::PARAM_STR);
		$stmt->bindParam(':advocacy_team_name',$advocacy_team_name, PDO::PARAM_STR);
		$stmt->bindParam(':advocate',$advocate, PDO::PARAM_STR);
		$stmt->bindParam(':advocate_id',$advocate_id, PDO::PARAM_STR);
		$stmt->bindParam(':advocate_name',$advocate_name, PDO::PARAM_STR);
		$stmt->bindParam(':campaign_id',$campaign_id, PDO::PARAM_STR);
		$stmt->bindParam(':campaign_title',$campaign_title, PDO::PARAM_STR);
		$stmt->bindParam(':card_type',$card_type, PDO::PARAM_STR);
		$stmt->bindParam(':check_number',$check_number, PDO::PARAM_STR);
		$stmt->bindParam(':client_proceeds',$client_proceeds, PDO::PARAM_STR);
		$stmt->bindParam(':corporate_matching_record',$corporate_matching_record, PDO::PARAM_STR);
		$stmt->bindParam(':custom_note_1',$custom_note_1, PDO::PARAM_STR);
		$stmt->bindParam(':custom_note_2',$custom_note_2, PDO::PARAM_STR);
		$stmt->bindParam(':custom_note_3',$custom_note_3, PDO::PARAM_STR);
		$stmt->bindParam(':custom_note_4',$custom_note_4, PDO::PARAM_STR);
		$stmt->bindParam(':custom_note_5',$custom_note_5, PDO::PARAM_STR);
		$stmt->bindParam(':description',$description, PDO::PARAM_STR);
		$stmt->bindParam(':fund_id',$fund_id, PDO::PARAM_STR);
		$stmt->bindParam(':designation_id',$designation_id, PDO::PARAM_STR);
		$stmt->bindParam(':designation_title',$designation_title, PDO::PARAM_STR);
		$stmt->bindParam(':designation_note',$designation_note, PDO::PARAM_STR);
		$stmt->bindParam(':firstname',$firstname, PDO::PARAM_STR);
		$stmt->bindParam(':lastname',$lastname, PDO::PARAM_STR);
		$stmt->bindParam(':email',$email, PDO::PARAM_STR);
		$stmt->bindParam(':middlename',$middlename, PDO::PARAM_STR);
		$stmt->bindParam(':phone',$phone, PDO::PARAM_STR);
		$stmt->bindParam(':timezone',$timezone, PDO::PARAM_STR);
		$stmt->bindParam(':title',$title, PDO::PARAM_STR);
		$stmt->bindParam(':updated',$updated, PDO::PARAM_STR);
		$stmt->bindParam(':city',$city, PDO::PARAM_STR);
		$stmt->bindParam(':country',$country, PDO::PARAM_STR);
		$stmt->bindParam(':country_code',$country_code, PDO::PARAM_STR);
		$stmt->bindParam(':country_name',$country_name, PDO::PARAM_STR);
		$stmt->bindParam(':state',$state, PDO::PARAM_STR);
		$stmt->bindParam(':street',$street, PDO::PARAM_STR);
		$stmt->bindParam(':street2',$street2, PDO::PARAM_STR);
		$stmt->bindParam(':zip',$zip, PDO::PARAM_STR);
		$stmt->bindParam(':donor_id',$donor_id, PDO::PARAM_STR);
		$stmt->bindParam(':donor_paid_fee',$donor_paid_fee, PDO::PARAM_STR);
		$stmt->bindParam(':email_opt_in',$email_opt_in, PDO::PARAM_STR);
		$stmt->bindParam(':expiration_month',$expiration_month, PDO::PARAM_STR);
		$stmt->bindParam(':expiration_year',$expiration_year, PDO::PARAM_STR);
		$stmt->bindParam(':external_tracking_id',$external_tracking_id, PDO::PARAM_STR);
		$stmt->bindParam(':final_date',$final_date, PDO::PARAM_STR);
		$stmt->bindParam(':frequency',$frequency, PDO::PARAM_STR);
		$stmt->bindParam(':gift',$gift, PDO::PARAM_STR);
		$stmt->bindParam(':gift_extra',$gift_extra, PDO::PARAM_STR);
		$stmt->bindParam(':hide_name',$hide_name, PDO::PARAM_STR);
		$stmt->bindParam(':last_four_digits',$last_four_digits, PDO::PARAM_STR);
		$stmt->bindParam(':net_proceeds',$net_proceeds, PDO::PARAM_STR);
		$stmt->bindParam(':organization_id',$organization_id, PDO::PARAM_STR);
		$stmt->bindParam(':payment_gateway_id',$payment_gateway_id, PDO::PARAM_STR);
		$stmt->bindParam(':payment_gateway_name',$payment_gateway_name, PDO::PARAM_STR);
		$stmt->bindParam(':payment_transaction_id',$payment_transaction_id, PDO::PARAM_STR);
		$stmt->bindParam(':recurring_count',$recurring_count, PDO::PARAM_STR);
		$stmt->bindParam(':reference_code',$reference_code, PDO::PARAM_STR);
		$stmt->bindParam(':sale_price',$sale_price, PDO::PARAM_STR);
		$stmt->bindParam(':schedule_id',$schedule_id, PDO::PARAM_STR);
		$stmt->bindParam(':sms_keyword',$sms_keyword, PDO::PARAM_STR);
		$stmt->bindParam(':status',$status, PDO::PARAM_STR);
		$stmt->bindParam(':subtype',$subtype, PDO::PARAM_STR);
		$stmt->bindParam(':transaction_set_id',$transaction_set_id, PDO::PARAM_STR);
		$stmt->bindParam(':tribute',$tribute, PDO::PARAM_STR);
		$stmt->bindParam(':campaign',$campaign, PDO::PARAM_STR);
		$stmt->bindParam(':content',$content, PDO::PARAM_STR);
		$stmt->bindParam(':medium',$medium, PDO::PARAM_STR);
		$stmt->bindParam(':source',$source, PDO::PARAM_STR);
		$stmt->bindParam(':vendor_id',$vendor_id, PDO::PARAM_STR);
		$stmt->bindParam(':embed_id',$embed_id, PDO::PARAM_STR);
		$stmt->bindParam(':embed_name',$embed_name, PDO::PARAM_STR);
		$stmt->bindParam(':donation_type',$donation_type, PDO::PARAM_STR);
		$stmt->bindParam(':created_datetime',$created_datetime, PDO::PARAM_STR);
		
		try{
			if($stmt->execute()){
				$rec_id = $this->conn->lastInsertId();
				return $rec_id;
			}else{
				return 0;
			}
		}catch( PDOException $e ){ // catch error and record in log file
			$message = $e->getMessage();
			$log_file_name = 'log/PDO-error-' . $id . '-' . time() . '.log'; 
			error_log($message, 3, $log_file_name);
			return 0;
		}
		
	}


	/**
	* Update Transaction record
	* @param array $data ( id = table_id, status = bbec_status )
	* @return integer $return
	*/
	public function update($data){
		extract($data);
		$stmt = $this->conn->prepare("UPDATE `".$this->transactions."` SET `bbec_status`=:bbec_status WHERE `table_id`=:table_id");
		$stmt->bindParam(':table_id',$table_id, PDO::PARAM_INT);
		$stmt->bindParam(':bbec_status', $status, PDO::PARAM_STR);
		
		try{
			if($stmt->execute()){
				if($stmt->rowCount() == 1){
					$this->log_action(array("action"=>"Queue Update","message"=>$table_id));
					return true;
				}else{
					//$message = 'Posted to BBEC successfully. But following QUEUE ID could not be sent to Done ' . $table_id;
					//$this->log_action(array("action"=>"Failed Transaction Update","message"=>$message));
					return false;
				}
			}else{ 
				$message = 'Posted to BBEC successfully. But following QUEUE ID could not be sent to Done ' . $table_id;
				$this->log_action(array("action"=>"Failed Transaction Update","message"=>$message));
				return false; 
			}
		}catch( PDOException $e ){
			$this->log_action(array("action"=>"Failed Transaction Update","message"=>$e->getMessage()));
			return false;
		}
				
	}
	
	/*
	* Get transactions flagged with status of "Error"
	*/
	public function get_transaction_errors(){
		
		$return_data = array();
		
		$query = "SELECT * FROM `".$this->transactions."` WHERE `bbec_status` IN ('Error')";
		$stmt = $this->conn->prepare($query);	
		$stmt->execute();

		try{
			if( $stmt->rowCount() > 0) {
				while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
				   $return_data[] = $result;
				}
				return $return_data;
			}else{
				return 0;
			}
		}catch(PDOException $e){
			print_r($e);
		}		
	}
	
	/**
	* @param array $data (action,message)
	*/
	public function log_action($data){
		parent::log_action($data);
	}

	public function get_header_transactions( $params ){
		$status = 'Pending';
		$order = 'DESC';
		$limit = 5;

		if(isset($params['limit'])){
			$limit = $params['limit'];
		}
		if(isset($params['status'])){
			$status = $params['status'];
		}	
		if(isset($params['order'])){
			$order = $params['order'];
		}		

		$return_data = array();
		if($status == 'All'){
			$query = "SELECT * FROM `".$this->transactions_header."` WHERE `bbec_status` IN ('Pending','Done') ORDER BY `created`" . $order . ' LIMIT ' . $limit;
		}else{
			$query = "SELECT * FROM `".$this->transactions_header."` WHERE `bbec_status`='".$status."' ORDER BY `created`" . $order . ' LIMIT ' . $limit;
		}

		$stmt = $this->conn->prepare($query);	
		$stmt->execute();

			if($stmt->rowCount() > 0) {
				while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
				   $return_data[] = $result;
				}
				return $return_data;
			}else{
				return 0;
			}
		
	}
	
	/**
	* update_multi
	* Update given fields for all transactions with given transaction_set_id
	*/ 
	public function update_multi($data){
		
		extract($data);
		$stmt = $this->conn->prepare("UPDATE `".$this->transactions."` SET `payment_gateway_id`=:payment_gateway_id, `frequency`=:frequency, `reference_code`=:reference_code,
		`recurring_count`=:recurring_count, `card_type`=:card_type, `custom_note_1`=:custom_note_1, `expiration_month`=:expiration_month, `expiration_year`=:expiration_year WHERE `transaction_set_id`=:transaction_set_id");
		
		$stmt->bindParam(':transaction_set_id', $transaction_set_id, PDO::PARAM_STR);
		$stmt->bindParam(':payment_gateway_id', $payment_gateway_id, PDO::PARAM_STR);
		$stmt->bindParam(':frequency', $frequency, PDO::PARAM_STR);
		$stmt->bindParam(':reference_code', $reference_code, PDO::PARAM_STR);
		$stmt->bindParam(':recurring_count', $recurring_count, PDO::PARAM_STR);
		$stmt->bindParam(':card_type', $card_type, PDO::PARAM_STR);
		$stmt->bindParam(':custom_note_1', $custom_note_1, PDO::PARAM_STR);
		$stmt->bindParam(':expiration_month', $expiration_month, PDO::PARAM_STR);
		$stmt->bindParam(':expiration_year', $expiration_year, PDO::PARAM_STR);
		try{
			if($stmt->execute()){
				//if($stmt->rowCount() == 1){
					$this->log_action(array("action"=>"Queue Update","message"=>$transaction_set_id));
					return true;
				//}else{
					//$message = 'Posted to BBEC successfully. But following QUEUE ID could not be sent to Done ' . $table_id;
					//$this->log_action(array("action"=>"Failed Transaction Update","message"=>$message));
					//return false;
				//}
			}else{ 
				$message = 'Transactions with the following Transaction Set ID could not be updated: ' . $transaction_set_id;
				$this->log_action(array("action"=>"Failed to Update Transactions","message"=>$message));
				return false; 
			}
		}catch( PDOException $e ){
			print_r($e->getMessage());
			$this->log_action(array("action"=>"Failed Transaction Update","message"=>$e->getMessage()));
			return false;
		}
				
	}

	public function update_header_transaction( $params ){
		extract( $params );
		$stmt = $this->conn->prepare("UPDATE `".$this->transactions_header."` SET `".$field."` = :value WHERE `transaction_set_id`=:transaction_set_id");
		$stmt->bindParam(':transaction_set_id', $transaction_set_id, PDO::PARAM_STR);
		//$stmt->bindParam(':field', $field, PDO::PARAM_STR);
		$stmt->bindParam(':value', $value, PDO::PARAM_STR);		
		
		$stmt->execute();
	}
	
}