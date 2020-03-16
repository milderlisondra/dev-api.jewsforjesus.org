<?php
class EMSHub extends Database{
	
	protected $subscribers = "subscribers";
	protected $emailaddress = '';
	protected $firstname = '';
	protected $lastname = '';
	protected $jewish = '';
	protected $believer = '';
	protected $address1 = '';
	protected $address2 = '';
	protected $city_region = '';
	protected $state_province = '';
	protected $country = '';
	
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

		$order = 'DESC';
		$limit = 5;

		if(isset($params['limit'])){
			$limit = $params['limit'];
		}
		if(isset($params['order'])){
			$order = $params['order'];
		}		
		
		$return_data = array();

		$query = "SELECT * FROM `".$this->subscribers."` WHERE `ems_saved`='Pending' ORDER BY `created_datetime`" . $order . ' LIMIT ' . $limit;

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
	* Save
	* @param array $data
	* @return mixed $result ( id on success; 0 on failure )
	*/
	public function save($data){

		$emailaddress = '';
		$firstname = '';
		$lastname = '';
		$heritage = '';
		$believer = '';
		$address1 = '';
		$address2 = '';
		$city_region = '';
		$state_province = '';
		$country = '';		
		
		extract($data);
		$stmt = $this->conn->prepare("INSERT INTO `".$this->subscribers."` 
			( emailaddress, 
			firstname, 
			lastname, 
			heritage, 
			believer, address1, address2,city_region,state_province, country) 
			VALUES (
			:emailaddress, 
			:firstname, 
			:lastname, 
			:heritage, 
			:believer, 
			:address1,
			:address2,
			:city_region,
			:state_province,
			:country)");
		
		// Bind parameters
		$stmt->bindValue(':emailaddress',$emailaddress, PDO::PARAM_STR);
		$stmt->bindValue(':firstname',$firstname, PDO::PARAM_STR);
		$stmt->bindValue(':lastname',$lastname, PDO::PARAM_STR);
		$stmt->bindValue(':heritage',$heritage, PDO::PARAM_STR);
		$stmt->bindValue(':believer',$believer, PDO::PARAM_STR);
		$stmt->bindValue(':address1',$address1, PDO::PARAM_STR);
		$stmt->bindValue(':address2',$address2, PDO::PARAM_STR);
		$stmt->bindValue(':city_region',$city_region, PDO::PARAM_STR);
		$stmt->bindValue(':state_province',$state_province, PDO::PARAM_STR);
		$stmt->bindValue(':country',$country, PDO::PARAM_STR);

		
		try{
			if($stmt->execute()){
				$rec_id = $this->conn->lastInsertId();
				return $rec_id;
			}else{
				return 0;
			}
		}catch( PDOException $e ){ // catch error and record in log file
			$message = $e->getMessage();
			$log_file_name = 'log/PDO-error' .  '-' . time() . '.log'; 
			error_log($message, 3, $log_file_name);
			return 0;
		}
		
	}


	/**
	* Update Transaction record
	* @param array $data ( id = id, field = field to be updated, field_value = value to be placed into given field )
	* @return integer $return
	*/
	public function update($data){
		extract($data);
		$stmt = $this->conn->prepare("UPDATE `".$this->subscribers."` SET `".$field."`=:field_value WHERE `id`=:id");
		$stmt->bindParam(':id',$id, PDO::PARAM_INT);
		$stmt->bindParam(':field_value', $field_value, PDO::PARAM_STR);
		
		try{
			if($stmt->execute()){
				if($stmt->rowCount() == 1){
					//$this->log_action(array("action"=>"Queue Update","message"=>$table_id)); 

					if(isset($cm_response)){
						$this->log_action(array("action"=>"Failure in EMS","message"=>print_r($cm_response,true)));
					}					
					return true;
				}else{
					//$message = 'Posted to BBEC successfully. But following QUEUE ID could not be sent to Done ' . $table_id;
					return false;
				}
			}else{ 
				$message = 'Posted to BBEC successfully. But following QUEUE ID could not be sent to Done ' . $table_id;
				//$this->log_action(array("action"=>"Failed Transaction Update","message"=>$message));
				return false; 
			}
		}catch( PDOException $e ){ print_r($e->getMessage());
			//$this->log_action(array("action"=>"Failed Transaction Update","message"=>$e->getMessage()));
			return false;
		}
				
	}
	
	private function determine_publication($params){
		
	}
	/**
	* @param array $data (action,message)
	*/
	public function log_action($data){
		parent::log_action($data);
	}

	
	
}