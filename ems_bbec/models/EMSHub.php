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

		$query = "SELECT * FROM `".$this->subscribers."` WHERE `EMSSaved`='Pending' ORDER BY `CreatedDatetime`" . $order . ' LIMIT ' . $limit;

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
		
		$EmailAddress = '';
		$FirstName = '';
		$LastName = '';
		$Language = '';
		$JFJBranch = '';
		$JFJStaffStatus = '';
		$MissionaryCode = '';
		$MissionaryAssignment = '';
		$FirstSourceAppealCode = '';
		$BBECSystemID = '';
		$BBECLookupID = '';
		$Tags = '';
		$ContactCode = '';
		$HouseholdFirstRecognitionAmount = '';
		$JFJStaffRole = '';
		$HouseholdLastRecognitionAmount = '';
		$HouseholdLargestRecognitionAmount = '';
		$LastDeputationDate = '';
		$Address = ''; 
		$State = ''; 
		$Country = ''; 
		$AddressLine1 = ''; 
		$AddressLine2 = ''; 
		$City = ''; 
		$ZIP = '';
		$LifetimeHouseholdRecognitionAmount = '';
		$DaysSinceHouseholdFirstRecognition = '';
		$DaysSinceHouseholdLastRecognition = '';
		$DaysSinceLastInteraction = '';
		$DaysSinceAddedtoBBEC = '';
		$InteractionSummary = '';
		$InteractionDate = '';
		$DaysSinceHouseholdLargestRecognition = '';
		$IsChurchContact = '';
		$Salutation = '';
		$UnbouncePageID = '';
		$UnbouncePageVariant = '';
		$UnbounceSubmissionDate = '';
		$LifetimeHouseholdRecognitionCount = '';
		$Age = '';
		$Gender = '';
		$DoyoubelieveinJesus = '';
		$PhoneNumber = '';

		
		extract($data);
		
		if( $heritage == '' || $believer == ''){
			$contact_code = 'Unknown';
		}
		if( strtolower($heritage) == 'no' && strtolower($believer) == 'yes' ){
			$contact_code = 'GB';
		}elseif( strtolower($heritage) == 'no' && strtolower($believer) == 'no'  ){
			$contact_code = 'UG';
		}elseif( strtolower($heritage) == 'yes' && strtolower($believer) == 'yes'  ){
			$contact_code = 'JB';
		}else{
			$contact_code = 'UJ';
		}
		
		$stmt = $this->conn->prepare("INSERT INTO `".$this->subscribers."` 
			( 
			EmailAddress,
			FirstName,
			LastName,
			Language,
			JFJBranch,
			JFJStaffStatus,
			MissionaryCode,
			MissionaryAssignment,
			FirstSourceAppealCode,	
			BBECSystemID,
			BBECLookupID,
			Tags,
			ContactCode,
			HouseholdFirstRecognitionAmount,
			JFJStaffRole,
			HouseholdLastRecognitionAmount,
			HouseholdLargestRecognitionAmount,
			LastDeputationDate,
			Address,
			State,
			Country,
			AddressLine1,
			AddressLine2,
			City,
			ZIP,
			LifetimeHouseholdRecognitionAmount,
			DaysSinceHouseholdFirstRecognition,
			DaysSinceHouseholdLastRecognition,
			DaysSinceLastInteraction,
			DaysSinceAddedtoBBEC,
			InteractionSummary,
			InteractionDate,
			DaysSinceHouseholdLargestRecognition,
			IsChurchContact,
			Salutation,
			UnbouncePageID,
			UnbouncePageVariant,
			UnbounceSubmissionDate,
			LifetimeHouseholdRecognitionCount,
			Age,
			Gender,
			DoyoubelieveinJesus,
			PhoneNumber,
			DataSource) 
			VALUES (
				:EmailAddress,
				:FirstName,
				:LastName,
				:Language,
				:JFJBranch,
				:JFJStaffStatus,
				:MissionaryCode,
				:MissionaryAssignment,
				:FirstSourceAppealCode,	
				:BBECSystemID,
				:BBECLookupID,
				:Tags,
				:ContactCode,
				:HouseholdFirstRecognitionAmount,
				:JFJStaffRole,
				:HouseholdLastRecognitionAmount,
				:HouseholdLargestRecognitionAmount,
				:LastDeputationDate,
				:Address,
				:State,
				:Country,
				:AddressLine1,
				:AddressLine2,
				:City,
				:ZIP,
				:LifetimeHouseholdRecognitionAmount,
				:DaysSinceHouseholdFirstRecognition,
				:DaysSinceHouseholdLastRecognition,
				:DaysSinceLastInteraction,
				:DaysSinceAddedtoBBEC,
				:InteractionSummary,
				:InteractionDate,
				:DaysSinceHouseholdLargestRecognition,
				:IsChurchContact,
				:Salutation,
				:UnbouncePageID,
				:UnbouncePageVariant,
				:UnbounceSubmissionDate,
				:LifetimeHouseholdRecognitionCount,
				:Age,
				:Gender,
				:DoyoubelieveinJesus,
				:PhoneNumber,
				:DataSource)");
		
		// Bind parameters
		$stmt->bindValue(':EmailAddress',$EmailAddress, PDO::PARAM_STR);
		$stmt->bindValue(':FirstName',$FirstName, PDO::PARAM_STR);
		$stmt->bindValue(':LastName',$LastName, PDO::PARAM_STR);
		$stmt->bindValue(':Language',$Language, PDO::PARAM_STR);
		$stmt->bindValue(':JFJBranch',$JFJBranch, PDO::PARAM_STR);
		$stmt->bindValue(':JFJStaffStatus',$JFJStaffStatus, PDO::PARAM_STR);
		$stmt->bindValue(':MissionaryCode', $MissionaryCode, PDO::PARAM_STR);
		$stmt->bindValue(':MissionaryAssignment', $MissionaryAssignment, PDO::PARAM_STR);
		$stmt->bindValue(':FirstSourceAppealCode', $FirstSourceAppealCode, PDO::PARAM_STR);		
		$stmt->bindValue(':BBECSystemID', $BBECSystemID, PDO::PARAM_STR);
		$stmt->bindValue(':BBECLookupID', $BBECLookupID, PDO::PARAM_STR);
		$stmt->bindValue(':Tags', $Tags, PDO::PARAM_STR);
		$stmt->bindValue(':ContactCode', $ContactCode, PDO::PARAM_STR);
		$stmt->bindValue(':HouseholdFirstRecognitionAmount', $HouseholdFirstRecognitionAmount, PDO::PARAM_STR);
		$stmt->bindValue(':JFJStaffRole', $JFJStaffRole, PDO::PARAM_STR);
		$stmt->bindValue(':HouseholdLastRecognitionAmount', $HouseholdLastRecognitionAmount, PDO::PARAM_STR);
		$stmt->bindValue(':HouseholdLargestRecognitionAmount', $HouseholdLargestRecognitionAmount, PDO::PARAM_STR);
		$stmt->bindValue(':LastDeputationDate', $LastDeputationDate, PDO::PARAM_STR);
		$stmt->bindValue(':Address', $Address, PDO::PARAM_STR);
		$stmt->bindValue(':State', $State, PDO::PARAM_STR);
		$stmt->bindValue(':Country', $Country, PDO::PARAM_STR);
		$stmt->bindValue(':AddressLine1', $AddressLine1, PDO::PARAM_STR);
		$stmt->bindValue(':AddressLine2', $AddressLine2, PDO::PARAM_STR);
		$stmt->bindValue(':City', $City, PDO::PARAM_STR);
		$stmt->bindValue(':ZIP', $ZIP, PDO::PARAM_STR);
		$stmt->bindValue(':LifetimeHouseholdRecognitionAmount', $LifetimeHouseholdRecognitionAmount, PDO::PARAM_STR);
		$stmt->bindValue(':DaysSinceHouseholdFirstRecognition', $DaysSinceHouseholdFirstRecognition, PDO::PARAM_STR);
		$stmt->bindValue(':DaysSinceHouseholdLastRecognition', $DaysSinceHouseholdLastRecognition, PDO::PARAM_STR);
		$stmt->bindValue(':DaysSinceLastInteraction', $DaysSinceLastInteraction, PDO::PARAM_STR);
		$stmt->bindValue(':DaysSinceAddedtoBBEC', $DaysSinceAddedtoBBEC, PDO::PARAM_STR);
		$stmt->bindValue(':InteractionSummary', $InteractionSummary, PDO::PARAM_STR);
		$stmt->bindValue(':InteractionDate', $InteractionDate, PDO::PARAM_STR);
		$stmt->bindValue(':DaysSinceHouseholdLargestRecognition', $DaysSinceHouseholdLargestRecognition, PDO::PARAM_STR);
		$stmt->bindValue(':IsChurchContact', $IsChurchContact, PDO::PARAM_STR);
		$stmt->bindValue(':Salutation', $Salutation, PDO::PARAM_STR);
		$stmt->bindValue(':UnbouncePageID', $UnbouncePageID, PDO::PARAM_STR);
		$stmt->bindValue(':UnbouncePageVariant', $UnbouncePageVariant, PDO::PARAM_STR);
		$stmt->bindValue(':UnbounceSubmissionDate', $UnbounceSubmissionDate, PDO::PARAM_STR);
		$stmt->bindValue(':LifetimeHouseholdRecognitionCount', $LifetimeHouseholdRecognitionCount, PDO::PARAM_STR);
		$stmt->bindValue(':Age', $Age, PDO::PARAM_STR);
		$stmt->bindValue(':Gender', $Gender, PDO::PARAM_STR);
		$stmt->bindValue(':DoyoubelieveinJesus', $DoyoubelieveinJesus, PDO::PARAM_STR);
		$stmt->bindValue(':PhoneNumber', $PhoneNumber, PDO::PARAM_STR);
		$stmt->bindValue(':DataSource', $DataSource, PDO::PARAM_STR);
		
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