<?php
error_reporting(E_ALL);

$action = "";
$mc_api_key = "1efee29538b5a65f51242451b8738b39-us10";
$interest = array(); // Array to hold Mailchimp interests for the list to be used

if(strtolower($_REQUEST['action']) == "subscribe" || strtolower($_POST['action']) == "subscribe" ){
    $action = strtolower(trim($_REQUEST['action'])); 
}
define("ENVIRONMENT","PRODUCTION");
if( ENVIRONMENT == 'STAGING'){
    $mc_list = "a91aac0940";
    // $interest['84aa090d6a'] = true; // Consent
}else{
    $mc_list = "5a70adfde6";
    $interest['9fc8c5a8ab'] = true; // Consent
}
extract($_REQUEST);

switch($action){
    
    case "subscribe":

        extract($_REQUEST);
        if(isset($subscriberHeritage)){
            $jewish = $subscriberHeritage;    
        }
        if(isset($subscriberFaith)){
            $believer = $subscriberFaith;  
        }
        $faith = $believer;
        $heritage = $jewish;
        
        switch( $mc_name ){
            case "us":
                if( ENVIRONMENT == 'STAGING'){
                    $mc_list = "a91aac0940";
                    $interest['56a0d20275'] = true; // Consent
                }else{
                    $mc_list = "5a70adfde6";
                    $interest['9fc8c5a8ab'] = true; // Consent
                }
                 
               
                $fname = $subscriberFirstName;
                $lname = $subscriberLastName;
                $firstsourcecode = $firstsourcecode;
                break;
			case "uk":
				$mc_list = 'f95e1f7205';


				$response_array = $_REQUEST;
				$response_array['mc_list'] = $mc_list;
				extract($_REQUEST);
				if($email_consent == true){
					$interest['12ead302cf'] = true;
				}
				if($email_general == true){
					$interest['21ac59f680'] = true;
				}
				if($email_newsletter == true){
					$interest['c6ff77d0e0'] = true;
				}
				if($phone_consent == true){
					$interest['a94a941c99'] = true;
				}
				if($sms_consent == true){
					$interest['835db5c382'] = true;
				}				
				break;
			case "fr":
				$mc_list = '4446c1b87a';
				$response_array = $_REQUEST;
				$response_array['mc_list'] = $mc_list;
				extract($_REQUEST);
				if($email_consent == true){
					$interest['e15f2297db'] = true;
				}
				if($email_general == true){
					$interest['f6450db615'] = true;
				}
				if($email_newsletter == true){
					$interest['559e8bca53'] = true;
				}
				if($phone_consent == true){
					$interest['cf64476bc6'] = true;
				}
				if($sms_consent == true){
					$interest['bde99b6d61'] = true;
				}				
				break;
        }
        
    	if (empty($heritage) || $heritage == false || strtolower($heritage) == 'gentile' || strtolower($heritage) == 'no' ){
    		if (empty($faith) || $faith == false || strtolower($faith) == 'no'){
    			$ccode = 'UG';
    			$interest['0ccc6e2cca'] = true; // Thought pieces for Jewish...
    		}else{
    			$ccode = 'GB';
    			$interest['f9ca9016e8'] = true; // Reports and stories...
    		}
    	}else{
    		if (empty($faith) || $faith == false || strtolower($faith) == 'no'){
    			$ccode = 'UJ';
    			$interest['0ccc6e2cca'] = true; // Thought pieces for Jewish...
    		}else{
    			$ccode = 'JB';
    			$interest['0ccc6e2cca'] = true; // Thought pieces for Jewish...
    			$interest['f9ca9016e8'] = true; // Reports and stories...
    		}
    	}

		// Builder address array
		$address = array();
		if( !empty($address1) && !empty($address2) && !empty($city) && !empty($state) && !empty($postal_code) && !empty($country) ){		
		
			if(!empty($address1)){
				$address['addr1'] = $address1;
			}
		
			if(!empty($address2)){
				$address['addr2'] = $address2;
			}
			if(!empty($city)){
				$address['city'] = $city;
			}			
			if(!empty($state)){
				$address['state'] = $state;
			}
			if(!empty($postal_code)){
				$address['zip'] = $postal_code;
			}
			if(!empty($country)){
				$address['country'] = $country;
			}		
		}
		 
		//structure merge fields
		$merge_fields = array();

		if(!empty($fname)){
			$merge_fields['FNAME'] = $fname;
		}
		if(!empty($lname)){
			$merge_fields['LNAME'] = $lname;
		}
		if(!empty($ctype)){
			$merge_fields['CTYPE'] = $ctype;
		}
		if(!empty($phone)){
			$merge_fields['PHONE'] = $phone;
		}
		if(!empty($language)){
			$merge_fields['LANGUAGE'] = $language;
		}
		if(!empty($salutation)){
			$merge_fields['SALUTATION'] = $salutation;
		}
		if(!empty($ccode)){
			$merge_fields['CCODE'] = $ccode;
		}
		if(!empty($firstsourcecode)){
			$merge_fields['1STSRCCODE'] = $firstsourcecode;
		}
		if(!empty($address)){
			$merge_fields['ADDRESS'] = $address;
		}
		if(!empty($gdprdescription)){
			$merge_fields['GDPRDESC'] = $gdprdescription;
		}
		if(!empty($gdprlegaltext)){
			$merge_fields['GDPRLEGAL'] = $gdprlegaltext;
		}
		if(!empty($gdprterms)){
			$merge_fields['GDPRPTERMS'] = $gdprterms;
		}
		if(!empty($gdprtermslink)){
			$merge_fields['GDPRPPLINK'] = $gdprtermslink;
		}
		if(!empty($gdprprivacylink)){
			$merge_fields['GDPRTLINK'] = $gdprprivacylink;
		}

		$merge_fields['GDPRUPDATE'] = date('Y-m-d');

		if(!empty($page_id)){
			$merge_fields['UBPAGEID'] = $page_id;
			//only get date and variant if there's a page ID
			$merge_fields['UBDATE'] = date('Y-m-d');
			if(!empty($variant)){
				$merge_fields['UBVARIANT'] = $variant;
			}
		}

		$data = array();

		$data['apikey'] = '327bc696ee748bda14ccdd3feff9c64b-us10';
		$data['email_address'] = $email;
		$data['status_if_new'] = 'pending';

		if(!empty($merge_fields)){
			$data['merge_fields'] = $merge_fields;
		}

		$data['interests'] = $interest;

		if(!empty($ipaddress)){
			$data['ip_opt'] = $ipaddress;
		}

		$jdata = json_encode($data);
		$mc_endpoint = 'https://us10.api.mailchimp.com/3.0/lists/'.$mc_list.'/members/'.md5($email);

mail("milder.lisondra@jewsforjesus.org","CURL Request",print_r($jdata,true));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $mc_endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json', 'Authorization: Basic '.base64_encode('user:'.$mc_api_key)));
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jdata);
        $curl_response = curl_exec($ch);
        curl_close($ch);	
       
        mail("milder.lisondra@jewsforjesus.org","CURL Response",print_r($curl_response,true));
        break;
    case "update": 

        $data = array();
        $merge_fields = array();
        
		$data['apikey'] = $mc_api_key;
		$data['email_address'] = $email;
		//$data['status_if_new'] = 'subscribed';
		
        
        $mc_endpoint = 'https://us10.api.mailchimp.com/3.0/lists/'.$mc_list.'/members/'.md5($email);

$address1 = '100 Main Street';
$city = 'Livermore';
$state = 'CA';
$country = "164";
$zip = '94550';
		// Build address array
		$address = array();
		if( !empty($address1) && !empty($city) && !empty($state) && !empty($zip) && !empty($country) ){		
		
			if(!empty($address1)){
				$address['addr1'] = $address1;

			}
		
		//	if(!empty($address2)){
		//		$address['addr2'] = $address2;
		//	}
			if(!empty($city)){
				$address['city'] = trim($city);
			}			
			if(!empty($state)){
				$address['state'] = trim($state);
			}
			if(!empty($zip)){
				$address['zip'] = trim($zip);
			}
			if(!empty($country)){
				$address['country'] = $country;
			}		
		}
		
		
		if(!empty($address)){
			$merge_fields['ADDRESS'] = $address;
		}
		
		if(!empty($merge_fields)){
			$data['merge_fields'] = $merge_fields;
		}
		
		$jdata = json_encode($data);
		
		$request_made = "endpoint:" . $mc_endpoint . ":" . "\r\n";
		$request_made .= print_r($jdata,true); 
        mail("milder.lisondra@jewsforjesus.org","CURL Update Request",$request_made);


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $mc_endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json', 'Authorization: Basic '.base64_encode('user:'.$mc_api_key)));
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($jdata));
        $curl_response = curl_exec($ch);
        curl_close($ch);        
        mail("milder.lisondra@jewsforjesus.org","CURL Response for updating member",print_r($curl_response,true));
        
        var_dump($curl_response);
        
        
        break;
    
}

// TODO
// create function for curl request


function notify($data){
    extract($data);
    mail("milder.lisondra@jewsforjesus.org","Notification from MC API",print_r($data,true));
}

