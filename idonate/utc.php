<?php
phpinfo();
/* 	function convertToPST($utc_ts){
		$tz_from = new DateTimeZone('UTC');
		$tz_to = new DateTimeZone('America/Los_Angeles');

		$orig_time = new DateTime($utc_ts, $tz_from);
		$new_time = $orig_time->setTimezone($tz_to);
		print_r($new_time);
		print '<br/>';
		$pst_date = $new_time->date;
		return $pst_date;
		print '<br/>';
		//$newtimestamp = $new_time->format('m/d/YYYY h:i:s');
		//return $newtimestamp;
	}

	//print 'a '.strtotime(convertToPST('2019-11-20T03:37:10.439272+00:00'));
	//print '<br/>';
	//print strtotime(convertToPST('2019-11-20T03:37:10.439272+00:00'));
	print date("m/d/Y h:i:s",strtotime(convertToPST('2019-11-20T00:21:51.629015+00:00')));
	
	$month = (int) "1";
	$new_month = $month; 
	if($month < 10 ){
		$new_month = (string) $month;
		$new_month = "0" . $new_month;
	} */
	
	//print $new_month;
		$string_to_check = 'Trinity Baptist Church of missions from the Cathedral mountain pass';
		$firstname_count = check_for_organization($string_to_check);
		$lastname_count = check_for_organization($string_to_check);
		
		//print $firstname_count + $lastname_count;
		
		function check_for_organization($string_to_check){
			$church_income_stopwords = array('Church','Fellowship','Ministries','Iglesia','Assembly','Outreach','Center','Mission','Cathedral');
			
			$found_count = 0;
			
			foreach($church_income_stopwords as $stop_word){
				if (strpos(strtolower($string_to_check), strtolower($stop_word)) !== false) {
					//print 'found ' . $stop_word . ' within string ' . $string_to_check . '<br/>';
					$found_count++;
				}			
			}
			return $found_count;
		}		
		
		print md5('milder30305@gmail.com');