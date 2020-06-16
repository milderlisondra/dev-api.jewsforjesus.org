<?php

class Database{

	public $conn;
	public $error_log = 'ems_log';
	
	protected function __construct(){
		$this->conn = null;
		
		$server_identity = $_SERVER['SERVER_NAME'];

		switch($server_identity){
			case "localhost":
			case "local.ems-bbec.com":
				$this->db_host = 'localhost';
				$this->db_name = 'ems_bbec';
				$this->db_user = 'root';
				$this->db_pass = '';				
				break;
			case "dev-api.jewsforjesus.org":
				$this->db_host = 'localhost';
				$this->db_name = 'ems_bbec';
				$this->db_user = 'root';
				$this->db_pass = 'WDqY62Sa6';		
				break;
			case "api.jewsforjesus.org": // Production
				$this->db_host = 'localhost';
				$this->db_name = 'apijfj_idonate';
				$this->db_user = 'apijfj_siadmin';
				$this->db_pass = '1fSJWonhYpvO';				
				break;
			default:
				$this->db_host = 'localhost';
				$this->db_name = 'subscribers';
				$this->db_user = 'root';
				$this->db_pass = '';			
				break;
		}
		
        try{
            $this->conn = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name, $this->db_user, $this->db_pass);
            $this->conn->exec("set names utf8");
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $exception){
			$log_file_name = 'log/DB-error-' . time() . '.log'; 
			error_log($exception->getMessage(), 3, $log_file_name);
			die();
        }
 
        return $this->conn;
	}

	/**
	* log_admin_action
	* Log action made by an admin
	* @param $data array 
	*/
	protected function log_admin_action( $data ){
		    $stmt = $this->conn->prepare("INSERT INTO `".$this->admin_actions."` (Admin, Action, Object,Previous_Data, Updated_Data) VALUES (:Admin, :Action, :Object,:Previous_Data, :Updated_Data)");
			$stmt->bindParam(':Admin', $user);
			$stmt->bindParam(':Action',$action);
			$stmt->bindParam(':Object',$object);
			$stmt->bindParam(':Previous_Data',$previous_data, PDO::PARAM_STR);
			$stmt->bindParam(':Updated_Data', $updated_data, PDO::PARAM_STR);
			
			// set values
			$user = $data['user'];
			$action = $data['action'];
			$object = $data['object'];
			$previous_data = $data['previous_data'];
			$updated_data = $data['updated_data'];
			
			$stmt->execute();			
	}	
	
	protected function log_action( $data ){

			extract($data);
		    $stmt = $this->conn->prepare("INSERT INTO `".$this->error_log."` (action, message) VALUES (:action, :message)");
			$stmt->bindValue(':action', $action);
			$stmt->bindValue(':message',$message);
			try{
				$stmt->execute();	
			}catch(PDOException $e){
				print_r($e);
			}			
	}

	
}
